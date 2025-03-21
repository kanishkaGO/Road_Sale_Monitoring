
<?php 
function create_table($sql,$heading,$tabtype=0,$maxrows=100,$shownulls=1)
{
	$conn =oci_connect('e_do','e_do','172.23.0.91/wclprod');
if(!$conn)
{
$err=oci_error();
die("Error in connecting database" .$err['message']);
}
	$ret_val="";
	$stid = oci_parse($conn, $sql);
	if (!$stid)
	{
		$err=oci_error();
		die("Error in parsing the statement" .$err['message']);

	}

	$ex=oci_execute($stid);
	if (!$ex)
	{
		$err=oci_error();
		die("Error in executing the statement" .$err['message']);
	}
	
	$ret_val=$ret_val . "<table border='1' cellpadding='0' cellspacing='0' align='center' width='60%' class='ui-responsive'>";
	$ncols = oci_num_fields($stid);
	
	$rowno = 0;
	if ($tabtype > 0)
	{
		$ret_val=$ret_val . "<thead><th align='' colspan='"."2"."'>".$heading."</th></thead>";
		while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS) != false) && ($rowno++ < $maxrows))
		{
			for ($i = 1; $i <= $ncols; ++$i) 
			{
				$val = oci_result($stid,$i);
				if($val!=NULL || $shownulls>0)
				{
					$ret_val=$ret_val . "<tr>\n";
					$colname = oci_field_name($stid, $i);
    				$ret_val=$ret_val . "  <td><b>".htmlentities($colname, ENT_QUOTES)."</b></td>\n";
		    		$ret_val=$ret_val . "  <td>".($val!=NULL ? $val:"&nbsp;")."</td>\n";
					$ret_val=$ret_val . "</tr>\n"; 
				}
			}
		}
	}
	else
	{
		$ret_val=$ret_val . "<thead ><th align='' colspan='".$ncols."'>".$heading."</th></thead>";
		
		$ret_val=$ret_val . "<tr>\n";
		for ($i = 1; $i <= $ncols; ++$i) 
		{
			$colname = oci_field_name($stid, $i);
	   		$ret_val=$ret_val . "  <th><b>".htmlentities($colname, ENT_QUOTES)."</b></th>\n";
		}
		$ret_val=$ret_val . "</tr>\n"; 

		while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS) != false) && ($rowno++ < $maxrows))
		{
			$ret_val=$ret_val . "<tr>\n";
			for ($i = 1; $i <= $ncols; ++$i) 
			{
				$val = oci_result($stid,$i);
	    		$ret_val=$ret_val . "  <td>".($val!== null ? $val:"&nbsp;")."</td>\n";
			}
			$ret_val=$ret_val . "</tr>\n"; 
		}
	}
	$ret_val=$ret_val . "</table>\n";
	oci_free_statement($stid);
	oci_close($conn);

	return $ret_val;
}
?>