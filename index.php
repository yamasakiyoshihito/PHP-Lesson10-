<?php
session_start();
mb_internal_encoding("utf8");

//変数の初期化
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //POST処理
    //エスケープ処理
    $_SESSION["name"] = htmlentities($_POST["name"] ?? "", ENT_QUOTES);
    $_SESSION["mail"] = htmlentities($_POST["mail"] ?? "", ENT_QUOTES);
    $_SESSION["age"] = htmlentities($_POST["age"] ?? "", ENT_QUOTES);
    $_SESSION["password"] = htmlentities($_POST["password"] ?? "", ENT_QUOTES);
    $_SESSION["comments"] = htmlentities($_POST["comments"] ?? "", ENT_QUOTES);
    //バリデーションチェック
    $errors = validate_form();
    if (empty($errors)) {
        //エラーがなければ、完了ページ遷移
        header("Location:confirm.php");
    }
}

//バリデーションチェックを行う関数
function validate_form(){
    //エラーメッセージ初期化
    $form_errors = array();

    //1.氏名のバリデーション
    $input["name"] = trim($_POST["name"] ?? ""); //$_POST設定されいないときに備えて、null合体演算子で対応
    if(strlen($input["name"]) == 0) { //入力されているかの確認
        $form_errors["name"] = "氏名を入力してください";
    }

    //2.メールのバリデーション
    $input["mail"] = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);
    if (!$input["mail"]) {
        $form_errors["mail"] = "メールアドレスは正しい形で入力してください";
    }
    //3.年齢のバリエーション
    $options = array(
        "options" => array(
            "min_range" => 18,
            "max_range" => 65,
        )
    );
    $input["age"] = filter_input(INPUT_POST, "age", FILTER_VALIDATE_INT,$options); //誠意数かどうかのチェック
    if (is_null($input["age"]) || $input["age"] === false) {
        $form_errors["age"] = "年齢は18歳以上、65以下で入力してください";
    }

    //4.コメントのバリデーション
    $input["comments"] = trim($_POST["comments"] ?? ""); //$_POSTが設定されていない時に備えて、null合体演算子で対応
    if (strlen($input["comments"]) == 0) { //入力されいるかの確認
        $form_errors["comments"] = "コメントを入力してください";
    }

//5.パスワードのバリデーション
    $input["password"] = trim($_POST["password"] ?? ""); //$_POSTが設定されていない時に備えて、null合体演算子で対応
    if (strlen($input["password"]) == 0) { //入力されいるかの確認
        $form_errors["password"] = "パスワードを入力してください";
    }
    return $form_errors;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>フォームを作る</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1 class="form_title">登録フォーム</h1>
    <form method="POST" action="">
        <div class="item">
            <label>名前</label>
            <input type="text" class="text" size="35" name="name" value="<?php echo $_SESSION["name"] ?? ""; ?>">
            <?php if (!empty($errors["name"])):?>
                <p class="err_message"><?php echo $errors["name"]; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <label>メールアドレス</label>
            <input type="text" class="text" size="35" name="mail" value="<?php echo $_SESSION["mail"] ?? ""; ?>">
            <?php if (!empty($errors["mail"])) : ?>
                <p class="err_message"><?php echo $errors["mail"]; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <label>年齢</label>
            <input type="number" class="text" size="35" name="age" value="<?php echo $_SESSION["age"] ?? ""; ?>">
            <?php if (!empty($errors["age"])) : ?>
                <p class="err_message"><?php echo $errors["age"]; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <lable>パスワード</lable>
            <input type="password" class="text" size="35" name="password" value="<?php echo $_SESSION["password"] ?? ""; ?>">
            <?php if (!empty($errors["password"])) : ?>
                <p class="err_message"><?php echo $errors["password"]; ?></p>
            <?php endif; ?>
            </div>
        <div class="item">
            <label>コメント</label>
                <textarea cols="35" rows="7" name="comments"><?php echo $_SESSION["comments"] ?? ""; ?></textarea>
                <?php if (!empty($errors["comments"])) : ?>
                    <p class="err_message"><?php echo $errors["comments"]; ?></p>
                <?php endif; ?>
        </div>
        <div class="item">
            <input type="submit" class="submit" value="入力内容を確認する">
        </div>
    </form>
</body>

</html>