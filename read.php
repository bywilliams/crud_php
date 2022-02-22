<?php 

# checa se existe id e se ele não esta vazio 
if (isset($_GET["id"]) && !empty($_GET["id"])) {

    # chama a conexão com o BD
    require_once 'connection/conn.php';

    # prepara a seleção do statement 
    $sql = "SELECT * FROM employees WHERE id = ? ";
    
    # se a conexão entre o banco e a instrução for true
    if($stmt = mysqli_prepare($conn, $sql)){

        # ligar variaveis com placeholders
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        #setando parametros 
        $param_id = trim($_GET["id"]);

        # tentando executar a instrução 
        if(mysqli_stmt_execute($stmt)){

            # joga o resultado da instrução dentro da variavel
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                # aqui só precisamos de um resultado então não precisamos usar um while loop

                # contem apenas 1 linha 
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                # recupera os vaores individuais dos campos
                $name = $row["name"];
                $address = $row["address"];
                $salary = $row["salary"];
            }else{
                header("Location: error.php");
                exit();
            }


        }else{
            echo "Oops! algo deu errado, tente novamente!";
        }
    }

    # fecha o statement
    mysqli_stmt_close($stmt);

    # fecha a conexão com o BD
    mysqli_close($conn);

}else{

    #se a url não contem um id como parametro, redireciona para uma pagina de erro
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                    <h1 class="mt-5 mb-3">Visualizar Registro</h1>
                    <div class="form-group">
                        <label>Nome:</label>
                        <p><b><?php echo $row["name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Endereço:</label>
                        <p><b><?php echo $row["address"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Salario:</label>
                        <p><b><?php echo $row["salary"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Voltar</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>