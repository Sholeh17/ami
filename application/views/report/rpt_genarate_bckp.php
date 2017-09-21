
<html>
<title>Print Test</title>

<head>

<!-- <x-bootstrap> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs4.2/shared/include-ext.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs4.2/shared/options-toolbar.js"></script> -->
<!-- </x-bootstrap> -->

<!-- shared example code -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs4.2/shared/examples.js"></script>
<!-- <script type='text/javascript'     src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>  !-->

<style type="text/css">
    @media print {
        thead {display: table-header-group;}
    }
.div, .div td{
	vertical-align	: top;
	padding-top		: 1px!important;
	padding-bottom	: 1px!important;
	font-family		: "Lucida Console"!important;
	font-size		: 10pt!important;
	text-transform	: uppercase!important;
}
.bt {}
.bt td{
	border-top:1px solid #000000;
}
</style>
<?php if ($is_excel == 0) { ?>
<script>
	function doPrint() {
		document.getElementById("btnP").style.display="none";
		window.print();
		document.getElementById("btnP").style.display="inline";
		
	}
</script>
<?php } ?>
</head>



<body>

<?php
if (!$result)
    die("Data Not Found!!!");
?>
    
<div class="div">
<?php if ($is_excel == 0) { ?><img id="btnP" src="<?php echo base_url(); ?>assets/images/btn_icon/print.gif" style="cursor: pointer; cursor: hand;" onClick="doPrint()"> <?php } ?>
<table border="0" cellpadding="0" cellspacing="0" width="1024">
<tbody>
	<tr>
		<td>
			<center><h3><?=$namemachine;?><br><?=$namereporttest;?></h3>						
						
						</center>
		</td>
	</tr>
</tbody>
</table>

<br /><br />
<div style="margin-bottom:4px;">
<table border="0" width="100%">
<tr><td width="40%">
<table border="0">
<tr><td>No Req</td><td>: <?=$rowformulir->no_req;?></td></tr>
<tr><td>Date Req</td><td>: <?=$rowformulir->date_request;?></td></tr>
<tr><td>Date Line</td><td>: <?=$rowformulir->date_line;?> </td></tr>
<tr><td>Sample</td><td>: <?=$rowformulir->sample;?></td></tr>
<tr><td>Tipe</td><td>: <?=$rowformulir->type_request;?></td></tr>
<tr><td>Criteria</td><td>: <?=$rowformulir->criteria;?></td></tr>
<tr><td>Scale</td><td>: <?=$rowformulir->scale;?></td></tr>
<tr><td>Purpose</td><td>: <?=$rowformulir->porpose;?></td></tr>
</table>
</div>
</td>
<td>
<div style="border-style: solid;border-width: 1px; width:300px;">
<table border="0" cellpadding="20" cellspacing="10">
	<tr><td>Create By,<br><br><br>___________</td><td>Approve By,<br><br><br>___________</td></tr>
</table>
</div></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
<tbody><thead>
<tr>
  	<td rowspan="2" style="width:20px; border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px; text-align: left;">
  		#
  	</td>
	<td rowspan="2" style="width:270px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;">
  		Compound
  	</td>	
	<td rowspan="2" style="width:100px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;">
  		Type
  	</td>	
    
    <td colspan="<?=count($fields_machine["fields"]);?>" style="width:500px;border: 1px solid black;  text-align: center;"><?=$namereporttest;?></td>
    	
	</tr>

<tr>
	<?php 
	$jx = 0;
	foreach($fields_machine["textlabel"] as $v) { 
		$limitToleransi = $this->mf->get_limit_toleransi_compund($rowformulir->sample, $id_machinetest, trim($fields_machine['fields'][$jx]));
		if ($limitToleransi)
			$limitTolerens = "<br>".$limitToleransi->start_toleransi." - ".$limitToleransi->end_toleransi;
		else
			$limitTolerens = "";
	?>
	<td style="width:100px; border-style: solid none solid solid;border: 1px solid black; text-align: center;">
  		<?=$v.$limitTolerens;?>
  	</td>
    <?php $jx++; } ?>
    
</tr>

</thead>

<?php
$rowAvarage = array();
for ($i= 0; $i < count($result); $i++) {
?>
	<tr>
	<td style="border-left: 1px solid black; text-align: left;"><?php echo ($i+1); ?>&nbsp;</td>			
    <td style="border-left: 1px solid black; text-align: left;"><?php echo $result[$i]['namecompound']; ?>&nbsp;</td>
    <td style="border-left: 1px solid black; text-align: left;"><?php echo $result[$i]['no_type']; ?>&nbsp;</td>	
    <?php foreach($fields_machine["fields"] as $v) { ?>		
    <td style="border: 1px solid black; text-align: right;"><?php echo $result[$i][$v]; ?>&nbsp;</td>
    <?php $rowAvarage[$v][$i] = $result[$i][$v]; ?>
    <?php } ?>
	</tr>
<? }  ?>

<tr>
  <td colspan="3" style="border-top: 1px solid black; text-align:right">Avrg
  </td>
  <?php foreach($fields_machine["fields"] as $v) { ?>	
  <td style="border: 1px solid black; text-align: right;"><?php echo array_sum($rowAvarage[$v]) / count($rowAvarage[$v]); ?>&nbsp;</td>
   
   <?php } ?>
</tr>

<tr>
  <td colspan="4" style="border-top: 1px solid black; text-align:right">
  <div id="graph" style="text-align:center; margin-top:5px;"></div>
  </td>
</tr>
</table>




<?php 
if ($info_report_test->is_graph_report == "YES") {

?>


<script>
Ext.require('Ext.chart.*');
Ext.require(['Ext.Window', 'Ext.fx.target.Sprite', 'Ext.layout.container.Fit', 'Ext.window.MessageBox']);
Ext.require(['Ext.data.*']);

Ext.onReady(function() {

   var chartDataStore = Ext.create("Ext.data.ArrayStore", {
        storeId: "chartData",
        fields: [
			{ name: "field", type: "string"},
            { name: "avrg", type: "float"}
        ],
        data: [
            ["MH",4500],
            ["ML",566],
            ["TC 10",546.5],
            ["TC 50",566.5],
            ["TC 90",506.5]
        ]

    });
    
    Ext.define('graphX', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'series', type: 'string'},
            {name: 'y_field_data', type: 'string'},
            {name: 'x_field_data', type: 'string'},
            {name: 'x_categori_axis', type: 'string'}
        ]
    });

    var StoreGraph = Ext.create('Ext.data.Store', {
        model: 'graphX',
        proxy: {
            type: 'ajax',
            url : '../../data_graph/get_data_result_test_by_field',
            reader: {
                type: 'json',
                root: 'data',
                totalProperty: 'total'
            }
        },
        autoLoad: true
    });
 
     Ext.chart.theme.White = Ext.extend(Ext.chart.theme.Base, {
        constructor: function() {
           Ext.chart.theme.White.superclass.constructor.call(this, {
               axis: {
                   stroke: 'rgb(8,69,148)',
                   'stroke-width': 1
               },
               axisLabel: {
                   fill: 'rgb(8,69,148)',
                   font: '12px Arial',
                   'font-family': '"Arial',
                   spacing: 2,
                   padding: 5,
                   renderer: function(v) { return v; }
               },
               axisTitle: {
                  font: 'bold 18px Arial'
               }
           });
        }
    });

    var win = Ext.widget('form', {
        width: 700,
        height: 300,
        title: 'GRAPH',
        margin:'20 20 20 20',
        renderTo: 'graph',
        layout: 'fit',
        items: {
            id: 'chartCmp',
            xtype: 'chart',
            animate: true,
            shadow: true,
            store: StoreGraph,
            axes: [{
                type: 'Numeric',
                position: 'left',
                fields: ['y_field_data','x_field_data'],
                label: {
                    renderer: Ext.util.Format.numberRenderer('0,0')
                },
                //title: 'Number of Hits',
                grid: true,
                minimum: 0
            }, {
                type: 'Category',
                position: 'bottom',
                fields: ['series'],
                title: ''
            }],
            series: [{
                type: 'column',
                axis: 'left',
                highlight: true,
                tips: {
                  trackMouse: true,
                  width: 140,
                  height: 28,
                  renderer: function(storeItem, item) {
                    this.setTitle(storeItem.get('name') + ': ' + storeItem.get('data1') + ' $');
                  }
                },
                label: {
                  display: 'insideEnd',
                  'text-anchor': 'middle',
                    field: 'y_field_data',
                    renderer: Ext.util.Format.numberRenderer('0'),
                    orientation: 'vertical',
                    color: '#333'
                },
                xField: 'series',
                yField: 'y_field_data'
            }]
        }
    });
});
</script>

<?php
}
?>

</body>
</html>