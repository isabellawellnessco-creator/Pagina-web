<div class="sk-login-page-wrapper" style="max-width: 500px; margin: 60px auto; padding: 40px; background: #fff; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center;">
    <h2 style="color: #0F3062; margin-bottom: 20px;">Iniciar Sesión</h2>
    <p style="margin-bottom: 30px; color: #666;">Bienvenido de nuevo a Skin Cupid</p>

    <div class="sk-login-form-container">
        <?php wp_login_form(); ?>
    </div>

    <div style="margin-top: 20px; font-size: 14px;">
        <a href="/wp-login.php?action=lostpassword" style="color: #E5757E;">¿Olvidaste tu contraseña?</a>
    </div>

    <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
        <p>¿Nuevo aquí?</p>
        <a href="/account/register/" class="button" style="background-color: #F8F5F1; color: #0F3062; border: 1px solid #0F3062; padding: 10px 25px; border-radius: 25px; text-decoration: none; display: inline-block; margin-top: 10px;">Crear Cuenta</a>
    </div>
</div>

<style>
    .sk-login-form-container form {
        text-align: left;
    }
    .sk-login-form-container label {
        display: block;
        margin-bottom: 5px;
        color: #0F3062;
        font-weight: bold;
    }
    .sk-login-form-container input[type="text"],
    .sk-login-form-container input[type="password"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    .sk-login-form-container input[type="submit"] {
        width: 100%;
        background-color: #0F3062;
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.3s;
    }
    .sk-login-form-container input[type="submit"]:hover {
        background-color: #E5757E;
    }
</style>
