<?php
function sosanhgandung($var_primary,$var_csv)
	{	
			
		$tongsokitu = strlen($var_primary);	
		$sokitu_dasosanh = similar_text($var_primary,$var_csv);
		
		$percent = $sokitu_dasosanh/$tongsokitu * 100;
		
		if($percent > 50)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	
echo sosanhgandung('Cty CP Bê Tông Hà Thanh','Bê Tông Hà Thanh');