<html>

<style>
    .captcha{
font-size: 15px;
margin-right: 4px;

    }
</style>

<img src="{{ $message->embed(public_path('assets/logo/sbrt_logo.gif')) }}" alt="Logo" style="width: 100px">

<h2>  <span style="color: #069613">Congratulations..!.</span>  Your Account Has Been Successfully Created! </h2>

<div>

    <table>
        <tr>
            <td>Username:</td>
             <td>  <span class="captcha" style="font-size: x-large !important;"> {{ $username }} </span> </td>
        </tr>
        <tr>
            <td>default Password:</td> <td> <span class="captcha" style="font-size: x-large !important;"> 123456</span> </td>
        </tr>
    </table>

    <p>Click The link below to be redirected to login page</p>
    <a href="{{ $signInLink }}"> Login </a>
</div>


</html>
