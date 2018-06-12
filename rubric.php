<?
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

class Rubric
{
	private $criteria;
	private $scales;
	private $points;

	public function add()
	{
		$n = func_num_args();
		$args = func_get_args();
		if($n < 1)
			return;

		$a = Array();
		$a[] = $args[0];
		for($i=1; $i<$n; $i+=2) {
			$a[] = $args[$i+1];
			$a[] = $args[$i];
		}
		$this->criteria[] = $a;
	}

	public function toString()
	{
		$s = '<table class="rubric">';
		for($i=0; $i<count($this->criteria); $i++)
		{
			$c = $this->criteria[$i];
			$s .= '<tr><td>' .$c[0]. '</td>';
			for($j=1; $j<count($c); $j++)
			{
				$s .= '<td>' .$c[$j]. ': ';
				$j++;
				$s .= $c[$j]. '</td>';
			}
			$s .= '</tr>';
		}
		$s .= '</table>';

		return $s;
	}
}
?>
