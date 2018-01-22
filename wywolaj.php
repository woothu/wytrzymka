    <?php
class numeric
{
    public static function LU($A)
    {
        $n = count($A);
        $n1 = $n - 1;
        $P = array();
        for ($k = 0; $k < $n; ++$k)
        {
            $Pk = $k;
            $Ak = $A[$k];
            $max = abs($Ak[$k]);
            for ($j = $k + 1; $j < $n; ++$j)
            {
                $absAjk = abs($A[$j][$k]);
                if ($max < $absAjk)
                {
                    $max = $absAjk;
                    $Pk = $j;
                }
            }
            $P[$k] = $Pk;
            if ($Pk != $k)
            {
                $A[$k] = $A[$Pk];
                $A[$Pk] = $Ak;
                $Ak = $A[$k];
            }

            $Akk = $Ak[$k];
            for ($i = $k + 1; $i < $n; ++$i)
            {
                $A[$i][$k] /= $Akk;
            }
            for ($i = $k + 1; $i < $n; ++$i)
            {
                $Ai = &$A[$i];
                for ($j = $k + 1; $j < $n1; ++$j)
                {
                    $Ai[$j] -= $Ai[$k] * $Ak[$j];
                    ++$j;
                    $Ai[$j] -= $Ai[$k] * $Ak[$j];
                }
                if ($j === $n1)
                    $Ai[$j] -= $Ai[$k] * $Ak[$j];
            }
        }

        return array('LU' => $A, 'P' => $P);
    }


    public static function LUsolve($LUP, $b)
    {
        $LU = $LUP['LU'];
        $n = count($LU);
        $x = $b;
        $P = $LUP['P'];
        for ($i = $n - 1; $i !== -1; --$i)
            $x[$i] = $b[$i];
        for ($i = 0; $i < $n; ++$i)
        {
            $Pi = $P[$i];
            if ($P[$i] !== $i)
            {
                $tmp = $x[$i];
                $x[$i] = $x[$Pi];
                $x[$Pi] = $tmp;
            }

            $LUi = $LU[$i];
            for ($j = 0; $j < $i; ++$j)
            {
                $x[$i] -= $x[$j] * $LUi[$j];
            }
        }
        for ($i = $n - 1; $i >= 0; --$i)
        {
            $LUi = $LU[$i];
            for ($j = $i + 1; $j < $n; ++$j)
            {
                $x[$i] -= $x[$j] * $LUi[$j];
            }
            $x[$i] /= $LUi[$i];
        }
        return $x;
    }

    public static function solve($A, $b)
    {
        return self::LUsolve(self::LU($A), $b);
    }
}


if(isset($_POST['l'])){
    $l = ($_POST['l'])/1000;
    $wynik_sil = $_POST['sila']*(-1);
    $wynik_momentow = $_POST['m'];
    $wynik_ugietej = 0;

    $A = array(
        array(1, 1, 0), //rownanie sil
        array(0, $l, 1),//rownanie momentow
        array(-(1/6)*$l*$l*$l, 0, 0.5*$l*$l),//rownanie osi ugietej
      );
$b = array($wynik_sil, $wynik_momentow, $wynik_ugietej);

$wynn = numeric::solve($A, $b);
$ray1=$wynn[0];
$ray2=$wynn[1];
$Ma=$wynn[2];
$test = array();
$test['test1'] = $ray1;
$test['test2'] = $ray2;
$test['test3'] = '$Ma';
echo json_encode($test);}



//$ray1=$_POST['ray1'];
//$ray2=$_POST['ray2'];
//$Ma=$_POST['Ma'];
?>
