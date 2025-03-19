<?php
    namespace repository;
    use \PDO as PDO;
    class Db
    {
        //Connexion à la base de données
        private static $db;

        //Exécute une requête à la base de données et retourne les données. Retourne faux si l'exécution n'a pas fonctionné
        static function query($_request, $_params = null)
        {
            self::ensureConnected();
            $query = self::prepare($_request, $_params);
            if (!$query->execute())
            {
                return false;
            }
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }


        //Exécute une requête à la base de données et retourne la première ligne. Retourne faux si l'exécution n'a pas fonctionné
        static function queryFirst($_request, $_params = null)
        {
            self::ensureConnected();
            $query = self::prepare($_request, $_params);
            
			if(!$query->execute())
            {
                return false;
            }
			
            return $query->fetch();
        }

        //Exécute une instruction à la base de données et retourne le nombre de lignes affectées. Retourne faux si l'exécution n'a pas fonctionné
        static function execute($_request, $_params = null)
        {
            self::ensureConnected();
            $query = self::prepare($_request, $_params);
            if (!$query->execute())
            {
                return false;
            }
            return $query->rowCount();
        }

		
        //Exécute une instruction à la base de de données et si cette instruction est une insertion, retourne l'ID entré. Retourne -1 si l'exécution n'a pas fonctionné.
        static function createLastID($_request, $_params = null)
        {
            self::ensureConnected();
            $query = self::prepare($_request, $_params);
            if (!$query->execute())
            {
                error_log("PDO Error: " . print_r($query->errorInfo(), true));
                return -1;
            }
            return self::$db->lastInsertId();
        }




        //Remplace les points d'interrogation par les valeurs dans un tableau et retourne une requête préparée
        private static function prepare($_request, $_params = null)
        {
            self::ensureConnected();
            $count = count((array)$_params);
            $query = self::$db->prepare($_request);
            if (is_array($_params))
            {
                for ($i = 0; $i < $count; $i++)
                {
                    $query->bindParam($i + 1, $_params[$i]);
                }
            }
            else
            {
                $query->bindParam(1, $_params);
            }
            return $query;
        }

        //S'assure que la connexion est ouverte
        private static function ensureConnected()
        {
            if (!isset(self::$db))
            {
                self::connect();
            }
        }

        //Établi une connexion avec la base de données
        private static function connect()
        {
            try 
            {
                self::$db = new PDO("mysql:host=" . HOST . ";charset=utf8;dbname=" . DBNAME , USERNAME, PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            } 
            catch (PDOException $e) 
            {
                print "Erreur !: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    }
?>
