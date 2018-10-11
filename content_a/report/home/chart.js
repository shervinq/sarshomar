function chartDrawer()
{
  if($("#chartdiv").length == 1){highChart();}
}



function highChart()
{
  Highcharts.chart('chartdiv',
  {
    chart: {
      type: 'funnel',
      style: {
        fontFamily: 'IRANSans, Tahoma, sans-serif'
      }
    },
    title: {
      text: '{%trans "Survey detail"%}'
    },
    plotOptions: {
      series: {
        dataLabels: {
          enabled: true,
          format: '<b>{point.name}</b> ({point.y:,.0f})',
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
          softConnector: true
        },
        center: ['40%', '50%'],
        neckWidth: '30%',
        neckHeight: '25%',
        width: '80%'
      }
    },
    legend: {
      enabled: false
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
    series: [{
      name: '{%trans "Person"%}',
      data: [
        ['Website visits', 15654],
        ['Start', 4064],
        ['Complete', 846]
      ]
    }]
  });
}