function chartDrawer()
{
  if($("#chartdiv").length == 1){highChart();}
}



function highChart()
{
  var text = '{{allWordCloud}}';
  var lines = text.split(/[,. ]+/g),
    data = Highcharts.reduce(lines, function (arr, word) {
      var obj = Highcharts.find(arr, function (obj) {
        return obj.name === word;
      });
      if (obj) {
        obj.weight += 1;
      } else {
        obj = {
          name: word,
          weight: 1
        };
        arr.push(obj);
      }
      return arr;
    }, []);

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
    series: [{
      type: 'wordcloud',
      data: data,
      name: '{%trans "Count"%}'
    }],
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
    title: {
      text: '{%trans "Word cloud"%}'
    }
  }, function(_chart)
  {
    _chart.renderer.image('{{service.logo}}', 10, 5, 30, 30).add();
  }
  );
}