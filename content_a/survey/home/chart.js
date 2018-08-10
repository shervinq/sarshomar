function chartDrawer()
{
  if($("#chartdiv").length == 1){myChart();}


}


//-------------------------------------------------------------------------------------------------------
function myChart()
{
  am4core.useTheme(am4themes_animated);

  var chart = am4core.create("chartdiv", am4charts.XYChart);
  chart.data = null;

  var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
  categoryAxis.renderer.grid.template.location = 0;
  categoryAxis.dataFields.category = "key";
  categoryAxis.renderer.minGridDistance = 60;


  var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
  // valueAxis.title.text = '{%trans "Count"%}';

  var series = chart.series.push(new am4charts.ColumnSeries());
  series.dataFields.categoryX = "key";
  series.dataFields.valueY = "value";
  series.tooltipText = "{valueY.value}"
  series.columns.template.strokeOpacity = 0;


  chart.cursor = new am4charts.XYCursor();

  // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
  series.columns.template.adapter.add("fill", function (fill, target)
  {
    return chart.colors.getIndex(target.dataItem.index);
  });

  // var label = chart.plotContainer.createChild(am4core.Label);
  // label.text = '{%trans "Price Variation"%}';
  // label.x = 10;
  // label.y = 10;
}
