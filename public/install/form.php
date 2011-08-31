<form id="setup" method="post" action="install.php">
    <table class="form-table">
        <tr>
            <th scope="row"><label for="db">Nome do Banco de Dados</label></th>
            <td><input name="db" type="text" id="name_bd" size="25" value="" /></td>
        </tr>
        <tr>
            <th><label for="user">Nome de Usuario</label></th>
            <td><input name="user" type="text" id="user_login" size="25" value="" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="password">Senha do Banco de Dados</label></th>
            <td><input name="password" type="password" id="password" size="25" value="" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="host">Servidor do Banco de Dados</label></th>
            <td><input name="host" type="text" id="user_login" size="25" value="" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="login">Email</label></th>
            <td><input name="login" type="text" id="login" size="25" value="" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="userpassword">Senha</label></th>
            <td><input name="userpassword" type="password" id="userpassword" size="25" value="" /></td>
        </tr>
        <tr>
            <th><label for="course">Criar curso de demonstracao</label></th>
            <td><input name="course" type="checkbox" id="course" value="1" /></td>
        <tr/>
    </table>
    <p class="step"><input type="submit" name="Submit" value="Proximo" class="button" /></p>
</form>