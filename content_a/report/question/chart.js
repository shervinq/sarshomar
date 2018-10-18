function chartDrawer()
{
  if($("#chartdiv").length == 1){highChart();}
}



function highChart()
{

var myData = {{dataTable.value | raw}};
var dataSum = {{dataTable.value_all | raw}};
// for (var i=0;i < myData.length;i++)
// {
//   dataSum += myData[i]
// }

Highcharts.chart('chartdiv',
{
  chart: {
    zoomType: 'x',
    style: {
      fontFamily: 'IRANSans, Tahoma, sans-serif'
    }
  },
  title: {
    text: '{{questionDetail.title}}'
  },
  xAxis: [{
    categories: {{dataTable.categories | raw}},
    crosshair: true
  }],
  yAxis: [{ // Primary yAxis
    labels: {
      format: '{value}',
      style: {
        color: Highcharts.getOptions().colors[0]
      }
    },
    // tickInterval:1,
    title: {
      text: '{%trans "Count"%}',
      useHTML: Highcharts.hasBidiBug,
      style: {
        color: Highcharts.getOptions().colors[0]
      }
    }
  }],
  tooltip: {
    useHTML: true,
    borderWidth: 0,
    shared: true
  },
  plotOptions: {
      series: {
          stacking: 'normal',
          grouping: false,
          shadow:false,
          borderWidth:0,
          dataLabels:{
              enabled:true,
              formatter:function() {
                  var pcnt = (this.y / dataSum) * 100;
                  return Highcharts.numberFormat(pcnt) + '%';
              }
          }
      }
  },
  exporting:
  {
    buttons:
    {
      contextButton:
      {
        menuItems:
        [
         'printChart',
         'separator',
         'downloadPNG',
         'downloadJPEG',
         'downloadSVG'
        ]
      }
    }
  },
  legend: {
    layout: 'vertical',
    align: 'left',
    x: 120,
    verticalAlign: 'top',
    y: 100,
    floating: true,
    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255,255,255,0.25)'
  },
  series: [
  {
    name: '{%trans "Incomplete survey"%}',
    type: 'column',
    data: dataSum,
    pointPadding: 0.4,
    tooltip: {
      valueSuffix: ' {%trans "Person"%}'
    }

  },
  {
    name: '{%trans "Complete survey"%}',
    type: 'column',
    data: myData,
    pointPadding: 0.3,
    tooltip: {
      valueSuffix: ' {%trans "Person"%}'
    }

  }
  ]
});
}