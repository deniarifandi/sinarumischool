<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

<div>
	<img src="<?php echo base_url(); ?>sdlogo.png" style="max-width: 100%;">
</div>

<div>
	<h2 style="text-align: center;">Chapter List</h2>
</div>

<div>
	<br>
	<table class="table table-bordered" style="font-size: 12px;">
		<thead>
			<tr>
				<th>Primary Chapter List</th>
		
				<th>Teaching Hours</th>
				<!-- <th>Password</th> -->
			</tr>
		</thead>
		<tbody>
			
			<?php
				$currentGrade = '';
				$currentSubject = '';
				$currentUnit = '';

				foreach ($data as $row) {
							
							if ($currentGrade != $row->tingkat_id) {
						    	echo '<tr>';
							    	echo '<td colspan="2">';
							        	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Grade {$row->tingkat_id}</strong><br>";
							        	$currentGrade = $row->tingkat_id;
							      	echo '</td>';

						      	echo '</tr>';
						      	$currentSubject = "";
						    }
						    if ($currentSubject != $row->subjek_nama) {
						    	echo '<tr>';
							    	echo '<td colspan="2">';
							        	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>{$row->subjek_nama}</strong><br>";
							        	$currentSubject = $row->subjek_nama;
							      	echo '</td>';
							    
						      	echo '</tr>';
						    }
						    if ($currentUnit != $row->unit_nama) {
						    	echo '<tr>';
							    	echo '<td colspan="1">';
							        	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>{$row->unit_nama}</i><br>";
							        	$currentUnit = $row->unit_nama;
							      	echo '</td>';
							      	echo '<td>';
						    			echo "{$row->unit_jp}<br>";
						    		echo '</td>';
						      	echo '</tr>';
						    }	

						    	echo '<tr>';
							    	echo '<td colspan="3">';
						    			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- {$row->subunit_nama}<br>";
						    		echo '</td>';
						    		
						    		
				    			echo '</tr>';
				}

				// for ($i=0; $i < count($data); $i++) { 
				// 	echo "<tr>";
				// 		echo "<td>".($i+1)."</td>";
				// 		echo "<td>".$data[$i]->subjek_nama."</td>";
				// 		echo "<td>".$data[$i]->tingkat_id."</td>";
				// 		echo "<td>".$data[$i]->unit_nama."</td>";
				// 		// echo "<td>******</td>";
				// 	echo "</tr>";
				// }
			?>
			
		</tbody>
	</table>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
