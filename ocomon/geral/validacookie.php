<?php 

# Inlcuir comentários e informações sobre o sistema
#
################################################################################
#                                  CHANGELOG                                   #
################################################################################
#  incluir um changelog
################################################################################

        $conexao = mysql_connect(localhost,root,"");
        $db = mysql_select_db(ocomon,$conexao);


         if (!empty($HTTP_COOKIE_VARS["usuario"]) or !empty($HTTP_COOKIE_VARS["senha"]))
        {
                $query = "SELECT * from usuarios where (login = '$usuario' and password = '$senha')";
                $resultado2 = mysql_query($query);

                if ($resultado2 == 0)
                {
                        echo "ERRO DE LOGIN - Tabela USUARIOS<br>";
                        exit;
                }
                if (mysql_numrows($resultado2) == 0)
                {
                        echo "ERRO DE LOGIN - Usuário $usuario<br>";
                        exit;
                }
        }
        else
        {
                echo "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;
                        URL=index.php\">";

        }

?>

