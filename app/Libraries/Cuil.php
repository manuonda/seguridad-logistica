<?php namespace App\Libraries;
/**
 * Description of Cuil
 *
 * @author Felipes
 */
class Cuil {

    private $dniStc;
    private $xyStc;
    private $digitoStc;

    /**
     * Metodo para generar un CUIT/CUIL.
     * 
     * @param dniInt DNI como int 
     * @param xyChar Sexo de la persona como char. 
     * Masculino: m - Femenino: f - Para Personas Juridicas: cualquier otro caracter 
     * 
     * @return El CUIT/CUIL como String 
     * */
    public function generar($dniInt, $xyChar, $nacionalidad = 1) {
        if ($nacionalidad == 1) {
            if ($xyChar == 'F' || $xyChar == 'f')
                $this->xyStc = 27;
            else
            if ($xyChar == 'M' || $xyChar == 'm')
                $this->xyStc = 20;
            else
                $this->xyStc = 30;
            $this->dniStc = $dniInt;
            $this->calcular();
        }else{
            $this->xyStc = 99;
            $this->dniStc = $dniInt;
            $this->digitoStc = strlen($nacionalidad) == 1 ? '0'.$nacionalidad : $nacionalidad;
        }
        return $this->formatear();
    }

    /**
     * Metodo estatico para generar un CUIT/CUIL. 
     *
     * @param dniInt DNI como int 
     * @param xyInt El prefijo del CUIT/CUIL como int 
     * 
     * @return El CUIT/CUIL como String 
     * */
    public function generar_b($dniInt, $xyInt) {
        $this->xyStc = $xyInt;
        $this->dniStc = $dniInt;
        $this->calcular();
        return $this->formatear();
    }

    /**
     * Metodo estatico para validar un numero de CUIT/CUIL. 
     * 
     * @param cuit N° de CUIT/CUIL como String 
     * 
     * @return Boolean: true si el CUIT/CUIL es correcto, false en caso contrario 
     * */
    public function validar($cuit) {
        $n = $cuit . lastIndexOf("-");
        $xyStr = $cuit . substring(0, 2);
        $dniStr = $cuit . substring($cuit . indexOf("-") + 1, $n);
        $digitoStr = $cuit . substring($n + 1, $n + 2);

        if ($xyStr . length() != 2 || $dniStr . length() > 8 || $digitoStr . length() != 1)
            return false;
        try {
            $this->xyStc = Integer . parseInt($xyStr);
            $this->dniStc = Integer . parseInt($dniStr);
            $this->digitoTmp = Integer . parseInt($digitoStr);
        } catch (Exception $e) {
            $e->getMessage();
            return false;
        }

        if (($this->xyStc != 20) && ($this->xyStc != 23) && ($this->xyStc != 24) && ($this->xyStc != 27) && ($this->xyStc != 30) && ($this->xyStc != 33) && ($this->xyStc != 34)) {
            return false;
        }
        $this->calcular();
        if ($this->digitoStc == digitoTmp && $this->xyStc == Integer . parseInt(xyStr))
            return true;
        return false;
    }

    /**
     * Metodo estatico que retorna el digito verificador de un CUIT/CUIL. 
     * 
     * @param xyInt El prefijo como int 
     * @param dniInt El DNI como int 
     * 
     * @return El digito como int. Si se modifico el prefijo (por 23 o 33) 
     * retorna 23x o 33x donde x es el digito 
     * */
    public function digito($xyInt, $dniInt) {
        $this->xyStc = $xyInt;
        $this->dniStc = $dniInt;
        $this->calcular();
        if ($xyInt == $this->xyStc)
            return $this->digitoStc;
        else
            return ($this->xyStc * 10 + $this->digitoStc);
    }

    /**
     * Metodo privado q da formato al CUIT como String 
     * */
    private function formatear() {
        return $this->xyStc . $this->completar($this->dniStc) . $this->digitoStc;
    }

    /**
     * Metodo privado q completa con ceros el DNI para q quede con 8 digitos 
     * */
    private function completar($dniStr) {
        $n = strlen($dniStr);
        while ($n < 8) {
            $dniStr = "0". $dniStr;
//dniStr = dniStr; 
            $n = strlen($dniStr);
            //$n = dniStr . length();
        }
        return $dniStr;
    }

    /**
     * Metodo privado q calcula el CUIT 
     * */
    public function calcular() {
        $acum = 0;
        $n = 2;
        $tmp1 = $this->xyStc * 100000000 + $this->dniStc;

        for ($i = 0; $i < 10; $i++) {
            $tmp2 = intval($tmp1 / 10);
            $acum += ($tmp1 - $tmp2 * 10) * $n;
            $tmp1 = $tmp2;

            if ($n < 7)
                $n++;
            else
                $n = 2;
        }
        $n = (11 - $acum % 11);
        if ($n == 10) {
            if ($this->xyStc == 20 || $this->xyStc == 27 || $this->xyStc == 24)
                $this->xyStc = 23;
            else
                $this->xyStc = 33;
            /* No es necesario hacer la llamada recursiva a calcular(), 
             * se puede poner el digito en 9 si el prefijo original era 
             * 23 o 33 o poner el dijito en 4 si el prefijo era 27 */
            $this->calcular();
        }else {
            if ($n == 11)
                $this->digitoStc = 0;
            else
                $this->digitoStc = $n;
        }
    }

}

