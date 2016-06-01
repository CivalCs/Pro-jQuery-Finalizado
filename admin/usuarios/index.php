<?php
if(function_exists(myAut)): valNivel('1'); else: header('Location: ../dashboard.php'); die; endif;
?>
<div class="content home">
    <h1 class="location">Gerenciar Usuários<span><a href="#" class="j_adduser">Novo usuário</a></span></h1><!--/location-->
    
    <div class="usuarios">
    
    	<ul class="users">
        	<?php
                $readUser = read('usuarios',"ORDER BY nome ASC");
                foreach($readUser as $user):
                    $nivel = ($user['nivel'] == '1' ? 'Super Admin' : 'Admin');
                    echo '<li id="'.$user['id'].'">';
                        $atts = array('class' => 'avatar', 'title' => $user['nome'], 'alt' => $user['nome']);
                        $avatar = gravatar( $user['email'], $s = 180, $d = 'mm', $r = 'g', $img = true, $atts = array() );
                        echo $avatar;
                        echo '<span class="nome">'.$user['nome'].'</span>';
                        echo '<span class="nivel">'.$nivel.'</span>';
                        echo '<span class="data">Cadastro: '.date('d/m/Y', strtotime($user['cadastro'])).'</span>';
                        echo '<div class="manage">';
                            echo '<a class="edit j_useredit" id="'.$user['id'].'" href="#">Editar</a>';
                            echo '<a class="dell j_userdelete" id="'.$user['id'].'" href="#">Excluir</a>';
                        echo '</div>';
                    echo '</li>';
                endforeach;
            ?>
        </ul><!--/users-->
        
    </div><!--/usuarios -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->