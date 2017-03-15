<?php 
	$msg='';
	$u = (!empty($_REQUEST['upd'])) ? intval($_REQUEST['upd']) : false;
	if ($u) $movimento=R::load('movimenti', $u);
	if (!empty($_REQUEST['importo'])) : 
		$movimento=(empty($_REQUEST['id'])) ?  R::dispense('movimenti') : R::load('movimenti', intval($_REQUEST['id']));
		$movimento->datamovimento = $_POST['datamovimento']; 
		$movimento->movimento = $_POST['movimento'];
		$movimento->categorie_id = (!empty($_POST['categorie_id'])) ? $_POST['categorie_id'] : null;
		$movimento->importo = $_POST['importo'];
		try {
			R::store($movimento);
		} catch (RedBeanPHP\RedException\SQL $e) {
			$msg=$e->getMessage();
		}
	endif;	
	
	if (!empty($_REQUEST['del'])) : 
		$movimento=R::load('movimenti', intval($_REQUEST['del']));
		try{
			R::trash($movimento);
		} catch (RedBeanPHP\RedException\SQL $e) {
			$msg=$e->getMessage();
		}
	endif;
	
	$movimenti=R::findAll('movimenti', 'ORDER by id ASC LIMIT 999');
	$categorie=R::findAll('categorie');

	
?>
<h1>
	<a href="index.php">
		Movimenti
	</a>
</h1>
<h4 class="msg"><?=$msg?></h4>
<div class="tablecontainer">
	<table class="full" style="table-layout:fixed">
		<colgroup>
			<col style="width:150px" />
		</colgroup>
		<thead>
			<tr>
				<th>Data</th>
				<th>Descrizione</th>
				<th>Categoria</th>
				<th>Importo</th>
				<th style="width:60px;text-align:center">Modifica</th>
				<th style="width:60px;text-align:center">Cancella</th>
			</tr>
		</thead>
		<tbody>
		<? foreach ($movimenti as $r) : ?>
		<? if ($u==$r->id) : ?>
			<tr>
				<td>
					<input type="date" name="datamovimento" value="<?=date('Y-m-d',strtotime($r->datamovimento))?>" onchange="chg(this)" autofocus />
				</td>
				<td>
					<input name="movimento" value="<?=$r->movimento?>" onchange="chg(this)" />
				</td>
				<td>
					<select name="categorie_id" placeholder="categoria" onchange="chg(this)" >	
						<option></option>
						<? foreach ($categorie as $cat) : ?>
							<option value="<?=$cat->id?>" <?=($r->categorie_id==$cat->id) ? 'selected' : '' ?> >  <?=$cat->categoria ?> </option>
						<? endforeach ?>
					</select>
				</td>
				<td>
					<input name="importo" type="number" step="any" value="<?=$r->importo?>" onchange="chg(this)"  style="text-align:right" />
				</td>
				<td>
					<form id="frm" method="POST" action="?p=movimenti">
						<input type="hidden" name="id" value="<?=$r->id?>" />
						<input type="hidden" name="datamovimento" value="<?=$r->datamovimento?>" />
						<input type="hidden" name="movimento" value="<?=$r->movimento?>" />
						<input type="hidden" name="categorie_id" value="<?=$r->categorie_id?>" />
						<input type="hidden" name="importo" value="<?=$r->importo?>" />
						<button type="submit" class="">
							Salva
						</button>
					</form>
				</td>
				<td>
					&nbsp;
				</td>							
			</tr>
		<? else : ?>
			<tr>
				<td>
					<?=date('d/m/Y',strtotime($r->datamovimento))?>
				</td>
				<td>
					<p>
						<?=$r->movimento?>
					</p>
				</td>
				<td>
						<?=($r->categorie_id) ? $r->categorie->categoria : ''?>
				</td>
				<td style="text-align:right" >
					<?=$r->importo?>
				</td>
				<td style="text-align:center" >
					<a href="?p=movimenti&upd=<?=$r['id']?>">
						Mod.
					</a>
				</td>
				<td style="text-align:center" >
					<a href="?p=movimenti&del=<?=$r['id']?>" tabindex="-1">
						x
					</a>
				</td>							
			</tr>		
		<? endif; ?>
		<? endforeach; ?>
		</tbody>
		<? if (!$u) : ?>
			<tfoot>
				<tr style="background:lightyellow">
					<td>
						<input type="date" name="datamovimento" value="<?=date('Y-m-d')?>" onchange="chg(this)"  autofocus />
					</td>
					<td>
						<input name="movimento" value="" onchange="chg(this)" />
					</td>
					<td>
						<select name="categorie_id" placeholder="categoria" onchange="chg(this)" >	
							<option></option>
							<? foreach ($categorie as $cat) : ?>
								<option value="<?=$cat->id?>">  <?=$cat->categoria ?> </option>
							<? endforeach ?>
						</select>
					</td>
					<td>
						<input name="importo" type="numer" step="any" value="" onchange="chg(this)" />
					</td>
					<td>
						<form id="frm" method="POST" action="?p=movimenti">
							<input type="hidden" name="datamovimento" value="<?=date('Y-m-d')?>" />
							<input type="hidden" name="movimento" value="" />
							<input type="hidden" name="categorie_id" value="" />
							<input type="hidden" name="importo" value="" />
							<button type="submit" class="button">
								Salva
							</button>
						</form>
					</td>
					<td>
						&nbsp;
					</td>							
				</tr>		
			</tfoot>
		<? endif; ?>
	</table>
</div>
<script>
	var chg=function(e){
		document.forms.frm.elements[e.name].value=(e.value) ? e.value : null
		//if (e.options && e.options[e.options.selectedIndex]) document.forms.frm.elements[e.name].value=e.options[e.options.selectedIndex].value
	}	
</script>