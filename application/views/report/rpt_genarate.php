
<html>
<title></title>

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
<table border="0" cellpadding="2" cellspacing="2" width="1024">
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
<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tbody><thead>
<tr>
  	<td rowspan="2" style="width:20px; border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px; text-align: left;">
  		#
  	</td>
	<td rowspan="2" style="border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;">
  		Compound
  	</td>	
	<td rowspan="2" style="width:100px;border-style: solid none solid solid; border-color: black -moz-use-text-color black black; border-width: 1px medium 1px 1px;">
  		Type
  	</td>	
    
    <td colspan="<?=count($fields_machine["fields"])+1;?>" style="width:500px;border: 1px solid black;  text-align: center;"><?=$namereporttest;?></td>
    	
	</tr>

<tr>
	<?php 
	$jx = 0;
        $cs = 0;
	foreach($fields_machine["textlabel"] as $v) { 
		$limitToleransi = $this->mf->get_limit_toleransi_compund($rowformulir->sample, $id_machinetest, trim($fields_machine['fields'][$jx]));
		if ($limitToleransi) {
			$limitTolerens = "<br>".$limitToleransi->start_toleransi." - ".$limitToleransi->end_toleransi;
                        $cs++;
                } else {
			$limitTolerens = "";
                }
	?>
	<td style="width:120px; border-style: solid none solid solid;border: 1px solid black; text-align: center;" nowrap>
  		<?=$v.$limitTolerens;?>
  	</td>
    <?php $jx++; } 
        if ($cs != 0) {
    ?>
        <td style="width:120px; border-style: solid none solid solid;border: 1px solid black; text-align: center;" nowrap>
  		STATUS
  	</td>
        <?php } ?>    
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
    <?php foreach($fields_machine["fields"] as $v) { 
?>		
    <td style="border: 1px solid black; text-align: right;"><?php echo $result[$i][$v]; ?>
    <?php 
        $checkTolerans = $this->mf->get_limit_toleransi_compund($rowformulir->sample, $id_machinetest, trim($v));
        $is_true =false;
        if (isset($checkTolerans->id)) {
            if ($checkTolerans->start_toleransi <= $result[$i][$v] && $result[$i][$v] <= $checkTolerans->end_toleransi) {
                $is_true = true && $is_true;
            } else {
                $is_true = false && $is_true;
            }
        }
        $rowAvarage[$v][$i] = $result[$i][$v]; 
    
    ?></td>
    <?php
        } 
        if ($is_true) 
            $status = "OK";
        else
            $status = "NOT";
        if ($cs != 0) {
    ?>
        <td style="border: 1px solid black; text-align: right;"><?=$status;?></td>
	</tr>
    <?php } } ?>

<tr>
  <td colspan="3" style="border-top: 1px solid black; text-align:right">Avrg
  </td>
  <?php 
  foreach($fields_machine["fields"] as $v) { 
  	//Counting to avarage
	$cnt = 0;
	$amount = 0;
  	foreach ($rowAvarage[$v] as $v1) {
		if (is_numeric($v1)  && $v1 != 0) {
			$amount += $v1;
			$cnt += 1;
		}
	}
	$res_avg = "-";
	if ($cnt > 0) {
		$res_avg = number_format(($amount / $cnt), 2,".",",");
	}
  ?>	
  <td style="border: 1px solid black; text-align: right;"><?php echo $res_avg;// array_sum($rowAvarage[$v]) /count($rowAvarage[$v]); ?></td>
   
   <?php } 
    if ($cs != 0) {
   ?>
  <td style="border: 1px solid black; text-align: right;"></td>
    <?php } ?>
</tr>

<tr>
  <td colspan="4" style="border-top: 1px solid black; text-align:right">
  <div id="graph" style="text-align:center; margin-top:5px;"></div>
  </td>
</tr>
</table>




<?php 
if ($is_excel == 1) {
    echo "</body>";
    echo "</html>";
    die;
}
$obj =& get_instance();
$datagraph = $obj->get_data_graph($_REQUEST['list_formulir_test'],$_REQUEST['list_report_test']);



if ($info_report_test->is_graph_report == "YES") {
?>


<script>
Ext.require('Ext.chart.*');
Ext.require(['Ext.Window', 'Ext.fx.target.Sprite', 'Ext.layout.container.Fit', 'Ext.window.MessageBox']);
Ext.require(['Ext.data.*']);

Ext.onReady(function() {
   
   <?php
        $name = "";
        $fields = "";
        foreach ($datagraph['graph_data']['data'] as $k=>$v) {
            $fields .= "'".$k."',";
        }
        $name = $datagraph['category'];
    ?>  
   
    
    Ext.define('graphX', {
        extend: 'Ext.data.Model',
        idProperty: 'Grapio',
        fields: ['<?=$name;?>', <?=substr($fields,0,-1);?>]
    });

    var store1 = Ext.create('Ext.data.Store', {
        model: 'graphX',
        proxy: {
            type: 'ajax',
            url : '../../data_graph/get_data_result_test_by_field',
            reader: {
                type: 'json',
                root: 'data',
                totalProperty: 'total'
            }
        }
    });

    store1.load({params:{id_formulir:<?=$_REQUEST['list_formulir_test'];?>,id_reports:<?=$_REQUEST['list_report_test'];?>}});
    
 <?php if ($datagraph['type_graph'] == "Line") { ?>   
    var chart = Ext.create('Ext.chart.Chart', {
            style: 'background:#fff',
            animate: true,
            store: store1,
            shadow: true,
            theme: 'Category1',
            legend: {
                position: 'right'
            },
            axes: [{
                type: 'Numeric',
                minimum: 0,
                position: 'left',
                fields: [<?=substr($fields,0,-1);?>],
                //title: 'Number of Hits',
                minorTickSteps: 1,
                grid: {
                    odd: {
                        opacity: 1,
                        fill: '#ddd',
                        stroke: '#bbb',
                        'stroke-width': 0.5
                    }
                }
            }, {
                type: 'Category',
                position: 'bottom',
                fields: ['<?=$name;?>']
                //,title: 'Month of the Year'
            }],
            series: [
            <?php    foreach ($datagraph['series'] as $v) { if ($v != "") { ?>
            {
                type: 'line',
                highlight: {
                    size: 7,
                    radius: 7
                },
                axis: 'left',
                xField: '<?=$name;?>',
                yField: '<?=$v;?>',
                markerConfig: {
                    type: 'cross',
                    size: 4,
                    radius: 4,
                    'stroke-width': 0
                }
            }, <?php } } ?>
            ]
        });

    
 <?php } else { ?>
    
    
     var chart = Ext.create('Ext.chart.Chart', {
            style: 'background:#fff',
            animate: true,
            shadow: true,
            store: store1,
            axes: [{
                type: 'Numeric',
                position: 'left',
                fields: [<?=substr($fields,0,-1);?>],
                label: {
                    renderer: Ext.util.Format.numberRenderer('0,0')
                },
                //title: 'Number of Hits',
                grid: true,
                minimum: 0
            }, {
                type: 'Category',
                position: 'bottom',
                fields: ['<?=$name;?>']
                //,title: 'Month of the Year'
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
                      console.log(item.yField);
                      this.setTitle(storeItem.get('<?=$name;?>') + ': ' + item.yField);
                  }
                },
                label: {
                  display: 'insideEnd',
                  'text-anchor': 'middle',
                    field: [<?=substr($fields,0,-1);?>],
                    renderer: Ext.util.Format.numberRenderer('0'),
                    orientation: 'vertical',
                    color: '#333'
                },
                xField: '<?=$name;?>',
                yField: [<?=substr($fields,0,-1);?>]
            }]
        });
    
 <?php } ?>
    
    var win = Ext.widget('form', {
        width: 900,
        height: 400,
        title: '<?=$namereporttest;?>',
        margin:'25 25 25 25',
        renderTo: 'graph',
        layout: 'fit',
        items: [chart]
    });
});
</script>

<?php
}
?>

</body>
</html>