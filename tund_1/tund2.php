<php
?>
echo $_post[news_title]
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Uudise lisamine</h1>
	<p>See leht on valminud õppetöö raames!</p>
<form>
	<label> Uuudise pealkiri</label>
	<input type= "text" name = "newsTitle" placeholder ="Uudise pealkiri"> <br>
	<label> Uudise sisu</label>
	<textarea name="nameEditor" placeholder ="Uudis"></textarea>
    <br>
    <input type="submit" name="newsBtn" Value="Salvesta uudis!">
</form>
</body>
</html>
</php>