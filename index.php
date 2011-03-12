<?php
//Classe per la gestione del database
class MysqlClass
{
    // parametri per la connessione al database
	private $nomehost = "localhost";
	private $nomeuser = "fahien";
	private $password = "progettosito";
	private $nomedb = "my_fahien";
	
	// controllo sulle connessioni attive
	private $attiva = false;
	
	// funzione per la connessione a MySQL
	public function connetti ()
	{
		if (!$this -> attiva)
   	{
			$connessione = mysql_connect ($this -> nomehost, $this -> nomeuser, $this -> password) or die (mysql_error ());
			$selezione = mysql_select_db ($this -> nomedb, $connessione) or die (mysql_error ());
			$this -> attiva = true;
		}
		else
		{
			return true;
		}
	}
	
	// funzione per la chiusura della connessione
	public function disconnetti ()
	{
		if ($this -> attiva)
		{
			if(mysql_close ())
			{
				$this -> attiva = false; 
				return true; 
			}
			else
			{
				return false; 
			}
		}
	}

	//Funzione per la lettura/scrittura sulla tabella utenti
    public function tabella_utenti ($dove, $cosa, $parametri)
    {
    	$cosa_sql = ‘WHERE ’;
    	foreach ($dove as $temp)
        {
        	if ($cosa_sql == ‘WHERE ’)
            {
        	    $cosa_sql .= “$dove = ‘$cosa’”;
            }
            else
            {
        	    $cosa_sql .= “ AND $dove = ‘$cosa’”;
            }
        }
    	$result = leggi (“utenti”, $dove, $cosa_sql);
    	return $result;
    }

    //funzione per l'esecuzione delle query 
    public function query ($sql)
    {
    	if (isset ($this -> attiva))
    	{
    		$sql = mysql_query ($sql) or die (mysql_error ());
    		return $sql;
    	}
    	else
    	{
    		return false; 
    	}
    }

	//funzione per la lettura dei dati tramite 3 argomenti
	public function leggi ($t, $v, $r = null)
    {
        if (isset ($this -> attiva))
        {
            $istruzione = ‘SELECT ‘;
            if (count($v) > 1)
            {
                for ($i = 0; $i < count ($v); $i++)
                {
                	if (is_string ($v[$i]))
                	$v[$i] = '"'.$v[$i].'"';
                }
                $v = implode (',',$v);
                $istruzione .= 'VALUES ('.$v.') ';
            }
    		else
    		{
    			$istruzione .= $v.’ ‘;
    		}
    		$istruzione .= ‘FROM ‘ . $t;
    		
    		if ( $r != null )
    		{
    			$istruzione .= ‘ ‘.$r;
    		}
    		$sql = mysql_query ( $istruzione );
    		return $sql;
        }
        else return false;
    }

    //funzione per l'inserimento dei dati tramite 3 argomenti
	public function inserisci ($t,$v,$r = null)
	{
		if (isset ($this -> attiva))
		{
			$istruzione = 'INSERT INTO '.$t;
			if ($r != null)
			{
				$istruzione .= ' ('.$r.')';
			}
			for ($i = 0; $i < count($v); $i++)
			{
				if (is_string ($v[$i]))
				$v[$i] = '"'.$v[$i].'"';
			}
			$v = implode (',',$v);
			$istruzione .= ' VALUES ('.$v.')';
			$query = mysql_query ($istruzione) or die (mysql_error ());
			}
			else
			{
				return false;
			}
		}
	}

	// funzione per l'estrazione dei record 
	public function estrai ($risultato)
	{
		if (isset ($this -> attiva))
		{
			$r = mysql_fetch_object ($risultato);
			return $r;
		}
		else
		{
			return false; 
		}
	}
}
?>
