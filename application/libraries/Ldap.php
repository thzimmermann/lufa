<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ldap
{
    public function isAuthentication($user, $pass)
    {
        $user = trim($user);
        $pass = trim($pass);
        if (strlen($user)>2 && strlen($pass)>3) {
            $ldap = ldap_connect(AD_SERVER);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
            @ldap_bind($ldap);
            $bind = @ldap_bind($ldap, $user."@satc.edu.br", $pass);
            if (!$bind) {
                ldap_get_option($ldap, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);
                $erro = explode(',', $extended_error)[2];
                $erro = explode(' ', $erro)[2];
                switch ($erro) {
                    case '525':
                        return ['id'=>11, 'text'=>'Usuário não encontrado'];
                        break;
                    case '52e':
                        return ['id'=>12, 'text'=>'Usuário ou senha incorretos'];
                        break;
                    case '530':
                        return ['id'=>13, 'text'=>'Não é permitido fazer login neste momento'];
                        break;
                    case '531':
                        return ['id'=>14, 'text'=>'Não é permitido login nesta estação de trabalho'];
                        break;
                    case '532':
                        return ['id'=>15, 'text'=>'Senha expirada! <a class="a-link" target="_blank" href="https://fs.satc.edu.br/adfs/portal/updatepassword/">Clique aqui</a> para redefinir sua senha.'];
                        break;
                    case '533':
                    case '534':
                        return ['id'=>16, 'text'=>'Conta desativada'];
                        break;
                    case '701':
                        return ['id'=>17, 'text'=>'Conta expirada'];
                        break;
                    case '773':
                        return ['id'=>18, 'text'=>'Usuário deve redefinir a senha'];
                        break;
                    case '775':
                        return ['id'=>19, 'text'=>'Conta de usuário bloqueada'];
                        break;
                }
            }
            return ['id'=>1, 'text'=>'Conectado com sucesso'];
        } else {
            return ['id'=>0, 'text'=>'Usuário ou senha invalidos'];
        }
    }
}
