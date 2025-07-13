<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

<div>
	<img src="<?php echo base_url(); ?>sdlogo.png" style="max-width: 100%;">
</div>

<div>
	<h2 style="text-align: center;">Teachers List</h2>
</div>

<div>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Teacher's_id</th>
				<th>Teacher's Name</th>
				<th>Username</th>
				<!-- <th>Password</th> -->
			</tr>
		</thead>
		<tbody>
			
			<?php
				for ($i=0; $i < count($data); $i++) { 
					echo "<tr>";
						echo "<td>".$data[$i]->guru_id."</td>";
						echo "<td>".$data[$i]->guru_nama."</td>";
						echo "<td>".$data[$i]->guru_username."</td>";
						// echo "<td>******</td>";
					echo "</tr>";
				}
			?>
			
		</tbody>
	</table>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
