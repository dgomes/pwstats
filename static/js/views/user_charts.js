
Highcharts.theme = {};// prevent errors in default theme
var highchartsOptions = Highcharts.getOptions();
 
var dataUrl = "http://joaobarraca.com/imeter/db/data.php?";
var maskMin = 0;
var maskMax = 0;
var min = 0;
var max = 0;
 
var masterChart,detailChart;
 
$(document).ready(function() {
    var $container = $('#container').css('position', 'relative');
    var $detailContainer = $('<div id="detail-container">').appendTo($container);
    var $masterContainer = $('<div id="master-container">').css({ position: 'absolute', top: 510, height: 80, width: '100%' }).appendTo($container);
    createMaster();
 
    $.get(dataUrl+'id=1', function(resp) {
        var data = eval("("+resp+")");
        jQuery.each(data.values, function(i, value) {
            data.values[i][0] = data.values[i][0] * 1000;
        });
        addSeries(data.values,"Data");
        drawMask();
        masterChart.redraw();
        detailChart.redraw();
 
    });
});
 
function addSeries(values,sname)
{
    if(maskMin == 0){
        if(values.length > 288)
            maskMin = values[values.length - 288][0];
        else
            maskMin = values[0][0];
    }
    if(maskMax == 0)
    {
        if(maskMax < values[values.length -1][0])
            maskMax = values[values.length -1][0];
    }
 
    if(min > values[0][0])
        min = values[0][0];
 
    if(max < values[values.length -1][0])
        max = values[values.length -1 ][0];
    var masterSeries ={ data: values, name: sname, color: highchartsOptions.colors[masterChart.series.length] };
    var detailSeries ={ data: "", name: sname, color: highchartsOptions.colors[masterChart.series.length] };
    masterChart.addSeries(masterSeries,false);
    detailChart.addSeries(detailSeries,false); 
}
 
function drawMask()
{
	var xAxis = masterChart.xAxis[0];
	var detailData = [];
	xAxis.removePlotBand('mask-before');
	xAxis.addPlotBand({
		id: 'mask-before',
		from: min,
		to: maskMin,
		color: Highcharts.theme.maskColor || 'rgba(0, 0, 0, 0.2)'
	});
 
	xAxis.removePlotBand('mask-after');
	xAxis.addPlotBand({
		id: 'mask-after',
		from: maskMax,
		to: max,
		color: Highcharts.theme.maskColor || 'rgba(0, 0, 0, 0.2)'
	});
	
	var sz = detailChart.series.length;
	var i = 0;
	for(i=0;i<sz;i++){
		detailData.push([]);
		jQuery.each(masterChart.series[i].data, function(j, point) {
			if (point.x > maskMin && point.x < maskMax) {
				detailData[i].push({
				x: point.x,
				y: point.y
				});
			}
		});
		detailChart.series[i].setData(detailData[i]);
	}
 
	var extremes = detailChart.xAxis[0].getExtremes();
 	var day = new Date(14*3600*1000).getTime();
	var minDate = new Date(extremes.min+day);
	var maxDate = new Date(extremes.max);
	var i = new Date();
	for(i=minDate;i<maxDate;i = new Date(i.getTime() + day))
	{
		var x = new Date(i.getFullYear(),i.getMonth(),i.getDate()).getTime();
		var lbl = {
	  		text: new Date(x).toDateString(),
		  	rotation: -90,
		  	align: 'center',
			y: 55,
			x: -5
		};
		detailChart.xAxis[0].addPlotLine({'value': x, 'color': 'gray', id:'plot-line-'+i,width:2, dashStyle: "ShortDot", label: lbl, zIndex: 1});
	}
 
}

function createMaster()
{
  masterChart = new Highcharts.Chart({
	 chart: {
		renderTo: 'master-container',
		reflow: false,
		borderWidth: 0,
		backgroundColor: null,
		marginLeft: 50,
		marginRight: 20,
		zoomType: 'x',
		events: {
			selection: function(event) {
			var extremesObject = event.xAxis[0];
			maskMin = extremesObject.min;
			 maskMax = extremesObject.max;
			drawMask();
			return false;
		   }
		}
	 },
	 title: {
		text: null
	 },
	 xAxis: {
		type: 'datetime',
		tickInterval: 1 * 24 * 3600 * 1000, // one week
		showLastTickLabel: true,
		maxZoom: 15 * 24 * 3600000, // fourteen days
		title: {
		   text: null
		}
	 },
	 yAxis: {
		gridLineWidth: 0,
		labels: {
		   enabled: false
		},
		title: {
		   text: null
		},
		min: 0.6,
		showFirstLabel: false
	 },
	 tooltip: {
		formatter: function() {
		   return false;
		}
	 },
	 legend: {
		enabled: false
	 },
	 credits: {
		enabled: false
	 },
	 plotOptions: {
		series: {
		   fillColor: {
			  linearGradient: [0, 0, 0, 70],
			  stops: [
				 [0, highchartsOptions.colors[0]],
				 [1, 'rgba(0,0,0,0)']
			  ]
		   },
		   lineWidth: 1,
		   marker: {
			  enabled: false
		   },
		   shadow: false,
		   enableMouseTracking: false,
		}
	 },
	 exporting: {
		enabled: false
	 }

  }, function(masterChart) {
	 createDetail(masterChart)
  });
  }

  // create the detail chart
  function createDetail(masterChart) {
  // prepare the detail chart
  var detailData = [];

  // create a detail chart referenced by a global variable
  detailChart = new Highcharts.Chart({
	 chart: {
		marginBottom: 20,
		renderTo: 'detail-container',
		reflow: false,
		marginLeft: 50,
		height: 500,
		marginRight: 20,
		marginTop: 50,
		style: {
		   position: 'absolute'
		},
		zoomType: 'x',
		events: {
			selection: function(event) {
			var extremesObject = event.xAxis[0];
			maskMin = extremesObject.min;
			 maskMax = extremesObject.max;
			drawMask();
			return false;
		   }
		}
	 },
	 credits: {
		enabled: false
	 },
	 title: {
		text: 'Power Consumption'
	 },
	 subtitle: {
	 },
	 xAxis: {
		type: 'datetime',
		tickPixelInterval: 100
	 },
	 yAxis: {
		title: null,
		maxZoom: 0.1,
		min: 0,
		minorTickInterval: "auto",
		majorTickinterval: "auto",
		maxPadding: 0.02
	 },
	 tooltip: {
		formatter: function() {
		   return '<tspan style="font-size: 10px" >'+Highcharts.dateFormat('%e %B %Y, %H:%M', this.x) + '</tspan><br/>'+
			  '<b>'+ (this.point.name || this.series.name) +': '+Highcharts.numberFormat(this.y, 0)+" Watts </b>";
		}
	 },
	 legend: {
		enabled: false
	 },
	 plotOptions: {
		series: {
		   shadow: true,
		   marker: {
			  enabled: false,
			  states: {
				 hover: {
					enabled: true,
					radius: 5
				 }
			  }
		   }
		}
	 },
	 exporting: {
		enabled: true
	 }

  });
 }