<html>

<head>

<!-- <x-bootstrap> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs4.2/shared/include-ext.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs4.2/shared/options-toolbar.js"></script> -->
<!-- </x-bootstrap> -->

<!-- shared example code -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs4.2/shared/examples.js"></script>
<!-- <script type='text/javascript'     src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>  !-->

<style>

	table{
		border-collapse:collapse;
		border-spacing: 0;	
	}
	
	th{		
		
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		height:25px;
		font-size:11px;
		text-align:center;
		vertical-align:middle;
		color:#FFF;
		font-weight:bold;
		border:solid #eceaea thin;
		border-bottom:none;
		background-color:#09F;
		
	}
	
	td{
	
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		font-size:11px;
		height:20px;
		color: #666;
		border:solid #eceaea thin;
		border-top:none;

	}
	.div_title {
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		font-size:14px;
		text-align:left;
		font-weight:bold;
		color:#666;
		margin-bottom:10px;
	}
	
	.div_sub_title {
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		font-size:12px;
		text-align:left;
		color:#666;
		margin-bottom:10px;
	}
</style>
</head>
<title>Report Summary Test Result</title>
<body style="margin:5 5 5 5">
	<div class="div_title">ACHIEVEMENT JOB SECTION LABORATORIUM
    	<br>
        <?=$_REQUEST['dte_frm'];?> s/d <?=$_REQUEST['dte_pto'];?>
       </div>
    <br>
    <br>
    
    
     <div id="graph_1"></div>
     <div style="margin-left:10px;">
        <table>
            <tr><th width="60px">PLAN</th><th width="60px">ACHIEVEMENT</th></tr>
            <tr>
                <td align="center"><?=(($count_plan) ? $count_plan : 0);?></td>
                <td align="center"><?=(($count_acheivement) ? $count_acheivement : 0);?></td>
             </tr>
        </table>
    </div>
    <br>
    <br>
    <br>
    
   
     
     <div id="graph_2"></div>
    <div style="margin-left:10px;">
    <table>
   		<tr>
        <th width="200px">-</th>
        <?php 
		#print_r($userList);
		#print "************************************************************************************************************************************************";
		#print_r($count_plan_by_user);
			foreach ($userList as $v) {
				echo "<th width='350px'>".$v->user_id."</th>";
			}
			
		?> 
        </tr>
        
        <tr><td>PLAN</td>
		<?php 
			#$arr_usr_plan = array();
			foreach ($userList as $puser) {
				$cntplu = 0;
				$arr_usr_graph[$puser->user_id]['plan'] = 0;
				foreach($count_plan_by_user as $cpu) {
					if ($cpu->user_create == $puser->user_id) {
						$cntplu += 1;
						$arr_usr_graph[$cpu->user_create]['plan'] += $cpu->cnt_acv;	
					}
				}
            	echo "<td>".$cntplu."</td>";
			}
		?> 	
        	
        </tr>
        <tr><td>ACHIEVEMENT</td>
        
        	<?php 
			foreach ($userList as $acuser) {
				$cntach = 0;
				$arr_usr_graph[$acuser->user_id]['achievement'] = 0;
				foreach($count_acheivement_by_user as $ach) {
					if ($ach->user_create == $acuser->user_id) {
						$cntach++;
						$arr_usr_graph[$ach->user_create]['achievement'] += 1;
					}
				}
            	echo "<td>".$cntach."</td>";
			}
			?> 	
        </tr>
       
    </table>
		</div>
    
    
   
    
<script>

 function numberFormat(duit)
	{
		//this.debug(duit);
		//duit = "0";
		if(!duit)duit = 0;
		if(duit && duit=="")duit = 0;
		if(typeof duit == "string"){
			duit = parseFloat(duit);
		}
		var Min = false;
		if(duit<0){
			Min = true;
			duit = -duit;
		}
		
		var sp		= typeof duit == "string" ? duit.split("."):(duit.toString()).split(".");
		var str		= sp[0];
		var l 		= (str.length)-1;
		var tmp 	= "";
		var hasil 	= "";
		var counter = 1;
		for(var i=l;i>=0;i--)
		{
			if((counter) % 3 == 0 && i!=0)
				tmp += str.charAt(i)+",";
			else
			{
				tmp += str.charAt(i);
			}
			counter++;
		}
	
		for(var i=(tmp.length)-1;i>=0;i--)
		{
			hasil += tmp.charAt(i);
		}
		return (Min?"-":"")+(parseInt(sp[1])?(sp[1]=="00"?hasil:hasil+"."+(sp[1].length==1?(sp[1])+"0":(sp[1]).substr(0,2))):hasil+".00");
	}


Ext.require('Ext.chart.*');
Ext.require(['Ext.layout.container.Fit', 'Ext.window.MessageBox']);

Ext.onReady(function () {
    var store = Ext.create('Ext.data.JsonStore', {
        fields: ['title', 'value'],
        data: [
                {title: 'Plan', value: <?=(($count_plan) ? $count_plan : 0);?>},
                {title: 'Achievement', value: <?=(($count_acheivement) ? $count_acheivement : 0);?>}
              ]
    });

  var chart = Ext.create('Ext.chart.Chart', {
            style: 'background:#fff',
            animate: true,
            shadow: true,
            store: store,
            axes: [{
                type: 'Numeric',
                position: 'left',
                fields: ['value'],
                label: {
                    renderer: Ext.util.Format.numberRenderer('0,0')
                },
                ///title: 'Number of Hits',
                grid: true,
                minimum: 0
            }, {
                type: 'Category',
                position: 'bottom',
                fields: ['title'],
                title: ''
            }],
            series: [{
                type: 'column',
				colorSet: ['#0000FF','#FFffff'],
                axis: 'left',
                highlight: true,
                tips: {
                  trackMouse: true,
                  width: 140,
                  height: 28,
                  renderer: function(storeItem, item) {
                    this.setTitle(storeItem.get('title') + ': ' + storeItem.get('value') + '');
                  }
                },
                label: {
                  display: 'insideEnd',
                  'text-anchor': 'middle',
                    field: 'value',
                    renderer: Ext.util.Format.numberRenderer('0'),
                    orientation: 'vertical',
                    color: 'white'
                },
                xField: 'title',
                yField: 'value',
				renderer: function(sprite, record, attr, index, store){
				    var item = chart.series.items[0].items[index].storeItem,
                    color = '#000000';
					if (index  == 1) {
						return Ext.apply(attr, {
							fill: 'red'
						});
					} else {
						return Ext.apply(attr, {
							fill: "#0066CC"
						});
					}
					/*if ((index % 2) && item.get('name') === 'January') {
						return Ext.apply(attr, {
							fill: color
						});
					}*/
					
					return attr;
				}
            }]
        });


    var panel1 = Ext.create('widget.panel', {
        width:200,
        height:300,
        //title: 'Stacked Bar Chart - Movies by Genre',
        renderTo: 'graph_1',
        layout: 'fit',
        tbar: [{
            text: 'Save Chart',
			hidden:true,
            handler: function() {
                Ext.MessageBox.confirm('Confirm Download', 'Would you like to download the chart as an image?', function(choice){
                    if(choice == 'yes'){
                        chart.save({
                            type: 'image/png'
                        });
                    }
                });
            }
        }],
        items: chart
    });
	
	
	
	
	
	var store2 = Ext.create('Ext.data.JsonStore', {
        fields: ['kategori', 'plan', 'achievement'],
        data: [
                <?php
					foreach ($arr_usr_graph as $k=>$v) {
				?> {kategori: '<?=trim(substr($k,0,3));?>', name_user: '<?=$k;?>', plan: <?=$v['plan'];?>, achievement: <?=$v['achievement'];?>},
				<?php
					}
				?>
              ]
    });
	
	var chart2 = Ext.create('Ext.chart.Chart',{
            animate: true,
            shadow: true,
            store: store2,
            legend: {
                position: 'right'
            },
            axes: [{
                type: 'Numeric',
                position: 'left',
                fields: ['plan', 'achievement'],
                title: false,
                grid: true,
                label: {
                    renderer: Ext.util.Format.numberRenderer('0,0')
                }
            }, {
                type: 'Category',
                //position: 'left',
				position: 'bottom',
                fields: ['kategori']
                //title: 'By User Input'
            }],
            series: [{
                type: 'column',
				colorSet: ['#0000FF','#FFffff'],
                axis: 'left',
                xField: 'kategori',
                yField: ['plan', 'achievement'],
                stacked: true,
				/*label: {
                  display: 'insideEnd',
                  'text-anchor': 'middle',
                    field: ['plan', 'achievement'],
                    renderer: Ext.util.Format.numberRenderer('0'),
                    orientation: 'vertical',
                    color: '#333'
                },*/
                tips: {
                    trackMouse: true,
                    width: 65,
                    height: 28,
                    renderer: function(storeItem, item) {
						var raw = storeItem.raw;
                        this.setTitle(String(item.value[1])+"->"+String(raw.name_user));
                    }
                }
            }]
        });
	
	
	var panel2 = Ext.create('widget.panel', {
        width: 1024,
        height: 400,	
        //title: 'Stacked Bar Chart - Movies by Genre',
        renderTo: 'graph_2',
		margins:'4 4 4 4',
        layout: 'fit',
        tbar: [{
            text: 'Save Chart',
			hidden:true,
            handler: function() {
                Ext.MessageBox.confirm('Confirm Download', 'Would you like to download the chart as an image?', function(choice){
                    if(choice == 'yes'){
                        chart.save({
                            type: 'image/png'
                        });
                    }
                });
            }
        }],
        items: chart2
    });
	
});


</script>    

    
</body>
</html>		