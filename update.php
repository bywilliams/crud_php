<?php

require_once 'connection/conn.php';

# feinindo as variaveis e iniciando sem valores
$name = $address = $salary = "";
$name_err = $address_err = $salary_err = "";


# processando os dados do form quando ele é submetido
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    //pegar o id que será atualizado
    $id = $_POST["id"];

    //validar name
    $input_name = trim($_POST["name"]);

    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    }else if(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    }else{
        $name = $input_name;
    }

    //validando endereço
    $input_address = trim($_POST['address']);

    if (empty($input_address)) {
        $address_err = "Please enter an address.";
    }else {
        $address = $input_address;
    }

    // validando salario
    $input_salary = trim($_POST['salary']);
    if (empty($input_salary)) {
        $salary_err = "Please enter a salary amount.";
    }else if(!ctype_digit($input_salary)){
        $salary_err = "Por favor entre com um valor positivo";
    }else{
        $salary = $input_salary;
    }

    //checando input errors antes de inserir na database
    if (empty($name_err) && empty($address_err) && empty($salary_err)) {
        // preparando a instrução statement para update
        $sql = "UPDATE employees SET name=?, address=?, salary=? WHERE id=?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_address, $param_salary, $param_id);

            //setando os paramentros
            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;
            $param_id = $id;

            //executando o statment prepardo
            if (mysqli_stmt_execute($stmt)) {
                header("location: index.php");
                exit();
            }else{
                echo "Oops! algo deu errado, tente de novo";
            }

        }

        //fechando o statement
        mysqli_stmt_close($stmt);
        
    }
    //fechando a conexão
    mysqli_close($conn);
}else{
    //checando existencia do id 
   if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
       //recebendo o parametro URL
       $id = trim($_GET["id"]);
       
       //preparando o select statment
       $sql = "SELECT * FROM employees WHERE id = ?";

       if ($stmt = mysqli_prepare($conn, $sql)) {
           mysqli_stmt_bind_param($stmt, "i", $param_id);

           // setando parametro
           $param_id = $id;

           //tentando executar o prepared statment
           if (mysqli_stmt_execute($stmt)) {
               $result = mysqli_stmt_get_result($stmt);

               if (mysqli_num_rows($result) == 1) {
                   $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                   $name = $row["name"];
                   $address = $row["address"];
                   $salary = $row["salary"];
               }else{
                   header("location: error.php");
                   exit();
               }
           }else{
               echo "Oops! algo deu errado, tente de novo";
           }
       }

       // fechando o statment
       mysqli_stmt_close($stmt);

       //fechando conexão
       mysqli_close($conn);
   }else{
       // se a  URL Não contem o id retorna para uma página de erro
       header("location: error.php");
       exit();
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Atualizando Registro</h2>
                    <p>Por favor edite as informações e clica em enviar para atualizar as informações do funcionário.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Nome:</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Endereço:</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Salario:</label>
                            <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>