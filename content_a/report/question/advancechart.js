function chartDrawer()
{
  if($("#chartdiv").length == 1){highChart1();}
}



function highChart1()
{

var data = {{advanceChart.chart | raw}};

// Splice in transparent for the center circle
Highcharts.getOptions().colors.splice(0, 0, 'transparent');


Highcharts.chart('chartdiv', {

  chart: {
    height: '100%'
  },

  title: {
    text: '{{questionDetail.title}}'
  },
  subtitle: {
    text: 'Advance chart'
  },
  series: [{
    type: "sunburst",
    data: data,
    allowDrillToNode: true,
    cursor: 'pointer',
    dataLabels: {
      format: '{point.name}',
      filter: {
        property: 'innerArcLength',
        operator: '>',
        value: 16
      }
    },
    levels: [{
      level: 1,
      levelIsConstant: false,
      dataLabels: {
        filter: {
          property: 'outerArcLength',
          operator: '>',
          value: 64
        }
      }
    }, {
      level: 2,
      colorByPoint: true
    },
    {
      level: 3,
      colorVariation: {
        key: 'brightness',
        to: -0.5
      }
    }, {
      level: 4,
      colorVariation: {
        key: 'brightness',
        to: 0.5
      }
    }]

  }],
  tooltip: {
    headerFormat: "",
    pointFormat: 'The answer of {point.name} is <b>{point.value}</b>'
  }
});
}