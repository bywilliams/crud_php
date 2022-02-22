<?php 

# chamando a conexão com o BD
require_once 'connection/conn.php';

# inciando as variaveis dos campos da tabela como vazias 
$name = $address = $salary = "";

# variaveis para retornar erro caso o campo name, address e salary não estejam preenchidos
$name_err = $address_err = $salary_err = "";

# processando os dados do form quando ele é submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    # validando $name
    # a função trim corta o que é desnecessário como espaços etc, ficando apenas o que foi digitado
    $input_name = trim($_POST["name"]);


    # se nome estiver vazio mostra a mensagem
    if (empty($input_name)) {
        $name_err = "Por favor Insira seu nome.";
    }
    # else if para checar se o nome inserido possui caracteres especiais através da função filter_var() se true não possui os caracteres
    # no caso usando o cimbolo da negação é checado se o retorno é falso, então dai possui os caracteres especiais 
    else if(!filter_var($input_name, FILTER_VALIDATE_REGEXP, 
    array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Por favor insira um nome valido.";
    }
    # se não for nenhuma das condições anteriores o nome é atribuido a variavel $name
    else{
          $name = $input_name;
    }

    # validando endereço
    $input_address = trim($_POST['address']);
    if(empty($input_address)){
        $address_err = "Por favor informe um endereço";
    }else{
        $address = $input_address;
      
    }

    # validando salario
    $input_salary = trim($_POST['salary']);
    if (empty($input_salary)) {
        $salary_err = "Por favor informe a quantia do salario";
    }
    # ctype_digit checa se todos os valores são numericos positivos 
    elseif(!ctype_digit($input_salary)){
        $salary_err = "Por favor coloque um valor positivo";
    }else{
        $salary = $input_salary;
    }
       
    
    # checando se há erros de input antes de inserir no banco de dados
    if (empty($name_err) && empty($address_err) && empty($salary_err)) {
        
        # preparando a instrução para ser inserida
        $sql = "INSERT INTO employees (name, address, salary)
        VALUES (?, ?, ?)";

        # checa através da função mysqli_prepare se preparação para uma execução SQL  retorna true e atribui para a variavel $stmt
        if ($stmt = mysqli_prepare($conn, $sql)) {

            # a função é usada para ligar variaveis aos placeholders
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_address, $param_salary);

            # setando os parametros
            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;

            # tentando executar a instrução preparada através da função mysqli_stmt_execute
            if (mysqli_stmt_execute($stmt)) {
                # se for true o registro será feito com suceso e retornara a landing page
                header("location: index.php");
                exit();
            }else{
                echo "Oops! algo deu errado, tente novamente";
            }

        }

     # fecha a declaração statement
     mysqli_stmt_close($stmt);

    }

    # fechando a conexão
    mysqli_close($conn);


}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .wrapper {
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
                    <h2 class="mt-5">Registrar um funcionário</h2>
                    <p>Por favor use este formulario para adicionar um funcionário ao banco de dados.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nome:</label>
                            <input type="text" name="name"
                                class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Endereço:</label>
                            <textarea name="address"
                                class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Salario:</label>
                            <input type="text" name="salary"
                                class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>