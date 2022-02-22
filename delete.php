<?php


// processar operação delete após a confirmação
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    //incluir arquivo do BD
    require_once "connection/conn.php";

    //preparar o statmente para delete
    $sql = "DELETE FROM employees WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        //atribuindo os placeholdes com bind variables
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        //setando parametros 
        $param_id = trim($_POST["id"]);

        //tentando executar o statmente preparado
        if (mysqli_stmt_execute($stmt)) {
            // registro deletado com sucesso, redirecionando para a landing page
            header("Location: index.php");
            exit();
        }else{
            echo "Oops, algo deu errado, tente novamente";
        }
    }

    //fechando o statmente
    mysqli_stmt_close($stmt);

    //fechando conexão
    mysqli_close($conn);

}else{
    //checando se o parametro do id existe
    if (empty(trim($_GET["id"]))) {
        // se não contem o parametro do if, redireciona para uma página de erro
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deletando Registros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Deletar Registro de funcionário</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Tem certeza que deseja deletar este funcionário?</p>
                            <p>
                                <input type="submit" value="Sim" class="btn btn-danger">
                                <a href="index.php" class="btn btn-secondary">Não</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
