<html>
<meta charset="utf-8">

<?php //PDO接続（データベース接続）

try{

	$dsn='mysql:dbname=tb210099db;host=localhost';
	$user='tb-210099';
	$password='hdLrZ5pfCy';
	$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

}catch	(PDOException $e) {
	


	echo "接続エラー発生:".htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8')."<br>";

	die();

	}



 //データベース内にテーブルを作成

try{
	$sql="CREATE TABLE IF NOT EXISTS mission_5"//tbtestがデータベース名
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date DATETIME,"
	. "pass TEXT"
	.");";
	$stmt=$pdo->query($sql);// SQL実行


}catch (PDOException $e) {

	echo "作成エラー発生:".htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8')."<br>";

	die();

	}







$newname="";		//中身がないときのエラー対策
$newcomment="";
$hen_ban="";





try{


if(!empty($_POST['name'])&&!empty( $_POST['comment'])){

    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $date = date("Y/m/d H:i:s");
    $pass=$_POST['pass1'];

	if(empty($_POST['henban'])){

	echo "入力を受け付けました"."<br>";
	
	}




//編集番号指定時の入力フォーム機能

if(!empty($_POST['henban'])){

	$henban=$_POST['henban'];
	$r=0;

	$sql = 'SELECT * FROM mission_5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();

	foreach($results as $row[]){



		if($henban==$row[$r][0]){ //編集番号指定フォームで指定された番号と同じとき



		//編集機能
		$id = $henban; //変更する投稿番号
		$name = $row[$r][1];
		$comment =$row[$r][2]; //変更したい名前、変更したいコメントは自分で決めること
		$sql = 'update mission_5 set name=:name,comment=:comment where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);

		$name = $_POST['name'];
    		$comment = $_POST['comment'];
    		$date = date("Y/m/d H:i:s");
    		$pass=$_POST['pass1'];

		$stmt->execute();

			echo "編集が完了しました"."<br>";


		}

		$r++;


}




		
}else{//通常入力フォーム機能


	$sql = 'SELECT * FROM mission_5';
	$stmt = $pdo->query($sql);
	$newresults = $stmt->fetchAll();



		//書込み機能
		$sql = $pdo -> prepare("INSERT INTO mission_5 (id,name, comment,date,pass) VALUES (:id,:name, :comment,:date,:pass)");
		$sql -> bindParam(':id',$id,PDO::PARAM_INT);
		$sql -> bindParam(':name',$name,PDO::PARAM_STR);
		$sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
		$sql -> bindParam(':date',$date,PDO::PARAM_STR);
		$sql -> bindParam(':pass',$pass,PDO::PARAM_STR);
	
		$name = $_POST['name'];
    		$comment = $_POST['comment'];
    		$date = date("Y/m/d H:i:s");
    		$pass=$_POST['pass1'];

		$sql -> execute();



}

}


}catch (PDOException $e) {

	echo "編集・新規エラー発生:".htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8')."<br>";

	die();

	}



//編集番号指定フォーム機能	

try{


if(!empty($_POST['hen_num'])){

	$f=0;
	$hen_num=$_POST['hen_num'];

	$sql = 'SELECT * FROM mission_5';
	$stmt = $pdo->query($sql);
	$hen_numresults = $stmt->fetchAll();

	foreach ($hen_numresults as $hen_numrow[]){

		
		if($hen_num==$hen_numrow[$f][0] && $_POST['pass2']==$hen_numrow[$f][4]){
	

			$newname=$hen_numrow[$f][1];
			$newcomment=$hen_numrow[$f][2];
			$hen_ban=$hen_numrow[$f][0];

	
		}


		$f++;

	}

}


}catch (PDOException $e) {

	echo "編集番号エラー発生:".htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8')."<br>";

	die();

	}





//削除フォーム機能

try{

if(!empty($_POST['delete'])){

	$delete=$_POST['delete'];	
	$s=0;

	$sql = 'SELECT * FROM mission_5';
	$stmt = $pdo->query($sql);
	$del_results = $stmt->fetchAll();


	foreach ($del_results as $del_row[]){

		if($delete == $del_row[$s][0]){

		$pass3=$_POST['pass3'];


		if($pass3 == $del_row[$s]['pass']){ //削除機能
		

		$id=$delete;
		$sql='delete from mission_5 where id=:id';
		$stmt=$pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

			echo "削除しました"."<br>";


		}else{

			echo "パスワードが間違っているため削除できません"."<br>";
		}



	}

	$s++;

}

}
	

}catch (PDOException $e) {

	echo "編集・新規エラー発生:".htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8')."<br>";

	die();

	}



?>


<!--名前、コメント、隠す編集フォーム-->


<form action="mission_5-1.php" method="post">



	<input type="text" name="name" value="<?=$newname ?>" placeholder="名前"><br>
	<input type="text" name="comment" value="<?=$newcomment ?>"  placeholder="コメント"><br>
	<input type="hidden" name="henban" value="<?=$hen_ban ?>" >
	<input type="text" name="pass1" placeholder="パスワード">

	<input type = 'submit'  value = '送信' ><br><br>





</form>



<!--表示させる編集フォーム-->
<form action="mission_5-1.php" method="post">


	<input type = "number" name = "hen_num"  placeholder="編集対象番号"/><br>
	<input type="text" name="pass2" placeholder="パスワード">
  	<input type = "submit"  value = "編集" /><br /><br />


</form>


<!--削除フォーム-->
<form action="mission_5-1.php" method="post">


	<input type = "number" name = "delete"  placeholder="削除対象番号"/><br>
	<input type="text" name="pass3" placeholder="パスワード">
  	<input type = "submit"  value = "削除" />


</form>






<!--表示部分-->

<?php //入力したデータをselectによって表示する

try{


	$sql = 'SELECT * FROM mission_5';
	$stmt = $pdo->query($sql);
	$dis_results = $stmt->fetchAll();

	foreach ($dis_results as $dis_row){
		//$rowの中にはテーブルのカラム名が入る
		echo $dis_row['id'].',';
		echo $dis_row['name'].',';
		echo $dis_row['comment'].',';
		echo $dis_row['date'].',';
		echo $dis_row['pass'].'<br>';
	echo "<hr>";

}


}catch (PDOException $e) {

	echo "編集・新規エラー発生:".htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8')."<br>";

	die();

	}




?>






</html>
