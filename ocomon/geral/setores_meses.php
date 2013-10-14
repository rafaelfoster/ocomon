<?PHP


  include ("layout.inc");
  echo "<blockquote>";

 //guarda o mes e o ano para fazer a estatistica
 $ano_mes = date("Y-m");


 //conecta no banco para realizar a estatística
 $db = mysql_connect("localhost","ocomon") or die ("erro server");
 mysql_select_db("ocomon",$db) or die ("erro banco");

 //$sql = "SELECT O.codigo as candidatos_codigo, C.PRO_ID as candidatos_PRO_ID,C.nome as candidatos_nome, C.email as candidatos_email, L.nome as linguas_nome, C1.nome as cursos1_nome, C2.nome as cursos2_nome, C3.cidade as cidades_cidade, C.fone as candidatos_fone, C.datainscr as candidatos_datainscr, C.datapagto as candidatos_datapagto, C.selecao as candidatos_selecao, C.endereco as candidatos_endereco, C.cep as candidatos_cep, C.uf as candidatos_uf, C.import as candidatos_import
 $sql = "select count(o.numero) total, l.local local
        from ocorrencias as o, localizacao as l
        where o.local = l.loc_id
        and o.data_abertura >= '$ano_mes-01 00:00:00'
        and o.data_abertura <= '$ano_mes-31 00:00:00'
        group by o.local
        order by total desc";

 //monta a tabela com as respostas

 echo "<table border=1 align='center'>";

 echo "<tr><td class='line'><b>Total de Atendimentos</td><td class='line'><b>Setor</td>";

 if (($result = mysql_query($sql)) && (mysql_num_rows($result) > 0) ) {
  while ($row = mysql_fetch_array($result)) {
         echo "<tr>";
         echo "<td class='line'>";
         echo $row["total"];
         echo "</td>";
         echo "<td class='line'>";
         echo $row["local"];
         echo "</td>";
         echo "</tr>";
         }
   echo "</tr>";
   echo "</tr>";

   echo"</table>";
  }

   echo "</blockquote>";



 ?>

