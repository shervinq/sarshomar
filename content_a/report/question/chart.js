function chartDrawer()
{
  if($("#chartdiv").length == 1){highChart();}
}



function highChart()
{

  var myData = {{dataTable.value | raw}};
  var dataSum = 0;
  for (var i=0;i < myData.length;i++)
  {
    dataSum += parseInt(myData[i]);
  }

  Highcharts.chart('chartdiv',
  {
    chart:
    {
      zoomType: 'x',
      style:
      {
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
    credits:
    {
        text: '{{service.title}}',
        href: '{{service.url}}',
        position:
        {
            align: 'left',
            x: 45,
            verticalAlign: 'top',
            y: 25
        },
        style: {
            color: '#15677b',
            fontWeight: 'bold'
        }
    },
    series: [

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
  }, function(_chart)
  {
    _chart.renderer.image('/static/images/logo/logo.png', 10, 5, 30, 30).add();
  }
  );
}