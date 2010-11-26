<?php

if (!defined( 'MN_TABLE' ) )
    define(MN_TABLE, "mn_payments");

require 'moip-php/MoIPStatus.php';

function mn_status()
{
	echo '<div class="wrap"><h2>MoIP Status</h2>';
	if (isset($_POST['mn_submit']))
	{
		
        	update_option('mn_moip_login', $_POST['mn_moip_login']);
		update_option('mn_moip_pass', $_POST['mn_moip_pass']);
		show_status();	
	}
	?>
	
	<p>O MoIPStatus é uma ferramenta que permite a consulta de saldo e a lista das dez últimas transações sem a necessidade de efetuar login no MoIP</p>
	
	<?php   
	if(!get_option('mn_moip_login') or !get_option('mn_moip_pass')){ ?>
		<p>Você precisa configurar a sua conta no MoIP antes de usar este plugin:</p>
		<form action="" method="post">
			<?php credentials_table(); ?>			
			<div class="submit">
			    <input type="submit" name="mn_submit" id="mn_submit" value="<?php _e('Verificar Status &raquo;') ?>" />
			</div>
		</form>

	<?php   
	} 
	else
	{
	?>
		<a href="#" onclick="jQuery('#tbl_credentials').show(); jQuery(this).hide(); return false;" />Alterar login/senha</a>
		<form action="" method="post">
			<div id="tbl_credentials" style="display:none">
				<?php credentials_table(); ?>
			</div>
			<div class="submit">
			    <input type="submit" name="mn_submit" id="mn_submit" value="<?php _e('Verificar Status &raquo;') ?>" />
			</div>
		</form>
	<?php
	}
	echo '</div>';
}


function credentials_table()
{
?>
	
	<table style="width: 850px; margin: 20px 0;" id="tblspacer" class="widefat fixed">
	
	  	<input type="hidden" name="setCredentials" value="1" /> 
	          <thead>
	              <tr>
	                  <th width="200px" scope="col">Editar configurações</th>
	                  <th scope="col">&nbsp;</th>
	              </tr>
	          </thead>
	      
	          <tbody>
	         
	          <tr>
	              <td class="titledesc" style="padding:12px 7px; vertical-align:top;">
	                  Login do MoIP:
	              </td>
	              
	              <td class="forminp" style="padding:12px 7px; vertical-align:top;">
	                  <input size="40"  type="text" name="mn_moip_login" value="<?=get_option('mn_moip_login');?>" />
	                  <br>
	                  <small>Você precisa ter uma <a href="http://www.moip.com.br/" target="_new" title="">conta no MoIP</a> antes de usar esta funcionalidade.</small>
	              </td>
	 
	              	             
	          </tr>
		 <tr>
			<td class="titledesc">Senha do MoIP: </td>
			<td class="forminp" style="padding:12px 7px; vertical-align:top;">
	                  <input size="40" type="password" name="mn_moip_pass" value="<?=get_option('mn_moip_pass');?>" />
                        </td>

		 </tr>
	  </table>

<?php
}

function show_status()
{
	$status = new MoIPStatus();
	$status->setCredenciais($_POST['mn_moip_login'],$_POST['mn_moip_pass']);
	$status->getStatus();
	?>
		<fieldset>
			<legend>Status do MoIP:</legend>
			
			<?php if($status->ultimas_transacoes==null){ ?>
				<p<strong>Você não possui nenhuma transação recente</strong></p>
			<?php } 
			else 
			{ 	
				echo '<table class="widefat fixed">';
				echo '<tr><th scope="col">Data</th><th>Nome</th><th>Pagamento</th><th>Adicional</th><th>Valor</th></tr>';
				foreach($status->ultimas_transacoes as $t)
				{
			?>
				<tr>
					<td><?php echo $t['data']; ?></td>
					<td><?php echo utf8_encode($t['nome']); ?></td>
					<td><?php echo $t['pagamento']; ?></td>
					<td><?php echo utf8_encode($t['adicional']); ?></td>
					<td><?php echo $t['valor']; ?></td>
				</tr>
			<?php 
				}
				echo '</table>';
			} 
			?>
			<p> Saldo total: <strong><?php echo $status->saldo; ?></strong></p>
		</fieldset>
	<?php
}
 
?>
