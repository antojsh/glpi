<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";	
}	

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}


$query2 = "
SELECT count( id ) AS chamados , DATEDIFF(solvedate,date) AS days
FROM glpi_tickets
WHERE solvedate IS NOT NULL
AND is_deleted = 0
AND glpi_tickets.date ".$datas."
".$entidade."
GROUP BY days ";
		
$result2 = $DB->query($query2) or die('erro');

$arr_keys = array();

while ($row_result = $DB->fetch_assoc($result2)) { 
	$v_row_result = $row_result['days'];
	$arr_keys[$v_row_result] = $row_result['chamados'];			
} 
	
$keys = array_keys($arr_keys);
$quant2 = array_values($arr_keys);

$conta = count($arr_keys);

/*for($i=0; $i <= $conta; $i++) {

	if($quant2[$i] != 0) {
		$till[$i] = $quant2[$i];
	}
	else {
		$till[$i] = 0;
	}	
	
	$arr_days[] += $till[$i];
}*/

$arr_more8 = array_slice($arr_keys,8);
$more8 = array_sum($arr_more8);


/*echo "teste";
var_dump($query2);
echo $conta;

echo "<br>keys";
print_r($arr_keys);

echo "<br>quant";
print_r($quant2);

echo "<br>days";
print_r($arr_days);
*/

echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graftime').highcharts({
            chart: {
            type: 'pie',
            options3d: {
				enabled: false,
                alpha: 45,
                beta: 0
            },
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Ticket Solving Period','dashboard')."'
            },
            /* legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                //floating: true,
                borderWidth: 0,
                backgroundColor: '#FFFFFF',
                adjustChartSize: true,
                format: '{series.name}: <b>{point.percentage:.1f}%</b>'
            }, */
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.y} - ( {point.percentage:.1f}% )</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    size: '85%',
                    innerSize: 90,
                    depth: 40,
                    dataLabels: {
									format: '{point.y} - ( {point.percentage:.1f}% )',
                   		   style: {
                        			color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        				}
                    },
                showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '".__('Tickets','dashboard')."',
                data: [  {
                        name: '< 1 " .__('day','dashboard')."',
                        y: ".$arr_keys[0].",
                        sliced: true,
                        selected: true
                    }, ['1 " .__('day','dashboard')."',  ".$arr_keys[1]." ], ['2 " .__('days','dashboard')."',  ".$arr_keys[2]." ],
                			['3 " .__('days','dashboard')."', ".$arr_keys[3]." ], ['4 " .__('days','dashboard')."',  ".$arr_keys[4]." ],
                			['5 " .__('days','dashboard')."',  ".$arr_keys[5]." ], ['6 " .__('days','dashboard')."',  ".$arr_keys[6]." ],
                			['7 " .__('days','dashboard')."',  ".$arr_keys[7]." ], ['8+ " .__('days','dashboard')."',  ".$more8." ]		]
            }]
        });
    });

		</script>"; 
		?>
