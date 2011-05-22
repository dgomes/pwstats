
Highcharts.theme = {};// prevent errors in default theme
Highcharts.setOptions({
	global: {
		useUTC: false
	 }
});

var highchartsOptions = Highcharts.getOptions();
var detailData = null;
var masterChart = null;

var dataUrl = base_url+"/series/reader";
var maskMin = 0;
var maskMax = 0;
var currentTime = new Date();
var min = currentTime.getTime() - 3600*24*1000;
var max = currentTime.getTime();


$(document).ready(function() {
	var $container = $('#container').css('position', 'relative');
	var $detailContainer = $('<div id="detail-container">').appendTo($container);
	var $masterContainer = $('<div id="master-container">').css({ position: 'absolute', top: 510, height: 80, width: '100%' }).appendTo($container);
	createMaster();
	load_data(min,max);
});

function  deviceSelectEvent(sender)
{
	if(sender.checked == false){
		reader_id = sender.value;
		load_data(min,max,sender.value);
	}else{
   		jQuery.each(masterChart.series, function(i, series) {
        		if(series.name == sender.value)
				series.remove(true);
		});
   		jQuery.each(detailChart.series, function(i, series) {
        		if(series.name == sender.value)
				series.remove(true);
		});

	}
}

function load_data(min,max,name)
{      
     
    $.get(dataUrl+"/"+reader_id+"/"+min+"/"+max+"/1", function(resp) {
        var data = eval("("+resp+")");
        var lastValue = 0;
		var lastTime = 0;
		
        jQuery.each(data.values, function(i, value) {
        	var t = lastTime+data.values[i][0];
        	var v =  lastValue+data.values[i][1];
        	data.values[i][0] = t*1000;
        	data.values[i][1] = v;
      		lastTime = t;
        	lastValue = v;
        });
        
        detailData = data.values;
        
        var masterData = new Array();
		var interval = (detailData[detailData.length-1][0] - detailData[0][0]) / 200;
		var last = 0;
		var sum = 0;
		var count = 0;
		for(i = 0; i < detailData.length;i++)
		{
			count++;
			sum += detailData[i][1];
				
			if(last + interval < detailData[i][0])
			{
				masterData.push(new Array(detailData[i][0],sum / count));
				last = detailData[i][0];
				sum = 0;
				count = 0;
			}
		}
		
		
		if(name == null)
			name = reader_id;
        
        setMask(data.values,name);
        var masterSeries ={ data: masterData, name: name, color: highchartsOptions.colors[masterChart.series.length] };
      	masterChart.addSeries(masterSeries);
        masterChart.redraw();
        
        load_data_detail(detailData[0][0],detailData[detailData.length-1][0],name);
    });
    
}

function load_data_detail(min,max,name)
{	
   	jQuery.each(detailChart.series, function(i, series) {
    	series.remove(true);
	})
		
	if(detailData ==  null)
	{
		$.get(dataUrl+"/"+reader_id+"/"+min+"/"+max+"/1", function(resp) {
			var data = eval("("+resp+")");
			detailData =new Array();
			var lastValue = 0;
			var lastTime = 0;
        	jQuery.each(data.values, function(i, value) {
        		var t = lastTime+data.values[i][0];
        		var v = lastValue+data.values[i][1];
            	detailData.push(new Array(t*1000,v));
        		lastTime = t;
        		lastValue = v;
        	});
        	
        	draw_detail_data(min,max,name);
		});
		return;
	}
	
	//Load missing left values
	if( detailData[0][0] > min)
	{
		jQuery.ajax({
     		url:   dataUrl+"/"+reader_id+"/"+min+"/"+detailData[0][0]+"/1",
     		success: function(resp) {
				var data = eval("("+resp+")");
        		var lastValue = 0;
				var lastTime = 0;
        		jQuery.each(data.values, function(i, value) {
        			var t = lastTime + data.values[i][0];
        			var v = lastValue+data.values[i][1];

        			data.values[i][0] = t*1000;
        			data.values[i][1] =  v;
            		lastTime = t;
        			lastValue = v;
        		});
        		
        		detailData = data.concat(detailData);
				draw_detail_data(min,max,name);
			},
	        async:   false
		});
	}

	//Load missing right values
	if( detailData[detailData.length - 1][0] < max)
	{
		jQuery.ajax({
     		url:   dataUrl+"/"+reader_id+"/"+detailData[detailData.length - 1][0]+"/"+max+"/1",
     		success: function(resp) {
				var data = eval("("+resp+")");
        		var lastValue = 0;
				var lastTime = 0;
        		jQuery.each(data.values, function(i, value) {
        			var t = lastTime + data.values[i][0];
        			var v = lastValue+data.values[i][1];
        			data.values[i][0] = t*1000;
        			data.values[i][1] = v;
            		lastTime = t;
        			lastValue = v;
        		});
        		detailData = detailData.concat(data);
				draw_detail_data(min,max,name);
			},
	        async:   false
		});
	}
	
	draw_detail_data(min,max,name);
}


function draw_detail_data(min,max,name)
{		
		var data = new Array();
		var interval = (max - min) / 150;
		var last = 0;
		var sum = 0;
		var count = 0;
		for(i = 0; i < detailData.length;i++)
		{
			if(detailData[i][0] >= min){
				count++;
				sum += detailData[i][1];
				
				if(last + interval < detailData[i][0])
				{
					data.push(new Array(detailData[i][0],sum / count));
					last = detailData[i][0];
					sum = 0;
					count = 0;
				}
			}
			if(detailData[i][0] >= max	)
				break;
		}
		
		
		var detailSeries ={ data: data, name: name, color: highchartsOptions.colors[masterChart.series.length] };
        detailChart.addSeries(detailSeries);
        
        draw_plot_lines(detailChart);
        detailChart.redraw();
}

function draw_plot_lines(chart)
{
 //Draw Vertical Lines
        var extremes = chart.xAxis[0].getExtremes();
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

function reload_chart(ts)
{
	maskMin = 0;
	maskMax = 0;
	min = currentTime.getTime() - ts*1000;
	max = currentTime.getTime();
	detailData = null;
	
   	jQuery.each(masterChart.series, function(i, series) {
        	series.remove(false);
	});
        jQuery.each(detailChart.series, function(i, series) {
        	series.remove(false);
	});
	
	load_data(min,max,null);
}

function setMask(values,sname)
{
    	if(maskMin == 0)
		{
    		maskMin = values[0][0];
    	}
   		if(maskMax == 0)
    	{
    		maskMax = values[values.length -1][0];
    	}
    	
        min = values[0][0];
       	max = values[values.length -1 ][0];
}

function drawMask()
{
	var xAxis = masterChart.xAxis[0];
	//var detailData = [];
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
	
	load_data_detail(maskMin,maskMax,null);
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
