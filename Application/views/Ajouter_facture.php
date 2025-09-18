<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = $_SESSION['message'] ?? "";
$status  = $_SESSION['status'] ?? "";
unset($_SESSION['message'], $_SESSION['status']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajouter une facture</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{font-family:Arial,sans-serif;background:#f0f8ff;margin:0;padding:0;color:#333}
.container{max-width:900px;margin:40px auto;padding:20px;background:#fff;border-radius:10px;box-shadow:0 4px 8px rgba(0,0,0,0.2)}
h2{color:#4682B4;text-align:center;margin:0 0 20px}
form{display:flex;flex-direction:column;gap:16px}
.row{display:flex;gap:12px;flex-wrap:wrap}
label{font-weight:bold;color:#4682B4}
input[type="number"],input[type="date"],select{padding:10px;font-size:16px;border-radius:6px;border:1px solid #4682B4;flex:1}
button{padding:10px 14px;font-size:16px;border-radius:6px;cursor:pointer;border:none}
.btn-primary{background:#1e90ff;color:#fff}
.btn-primary:hover{background:#4682b4}
.btn-secondary{background:#87CEEB;color:#fff}
.btn-danger{background:#dc3545;color:#fff}
.products{border:1px solid #1e90ff;border-radius:8px;padding:12px;background:#f8faff}
.product-row{display:grid;grid-template-columns:1.6fr 0.8fr 0.8fr 0.6fr;gap:10px;align-items:center;margin-bottom:10px}
.totals{display:flex;justify-content:flex-end;gap:12px;align-items:center;font-size:18px}
.success{color:green}
.error{color:red}
.muted{color:#666;font-size:14px}
</style>
</head>
<body>
<div class="container">
<h2>Ajouter une facture</h2>

<?php if ($message): ?>
<p class="<?php echo $status === 'error' ? 'error' : 'success'; ?>">
<?php echo htmlspecialchars($message); ?>
</p>
<?php endif; ?>

<form method="POST">
<div class="row">
    <div style="flex:1;">
        <label for="id_client">ID Client</label>
        <input type="number" name="id_client" id="id_client" min="1" required>
    </div>
    <div style="flex:1;">
        <label for="date_factures">Date</label>
        <input type="date" name="date_factures" id="date_factures" value="<?php echo date('Y-m-d'); ?>" required>
    </div>
</div>

<div class="products">
    <div class="product-row" style="font-weight:bold;color:#1e90ff;">
        <div>Produit</div><div>Prix (€/u)</div><div>Quantité</div><div>Ligne (€)</div>
    </div>
    <div id="productRows"></div>
    <button type="button" class="btn btn-secondary" id="addRowBtn">+ Ajouter un produit</button>
</div>

<div class="totals">
    <span>Total:</span><strong id="grandTotal">0.00 €</strong>
</div>

<input type="hidden" name="total_display" id="total_display" value="0">

<div style="display:flex;gap:10px;justify-content:flex-end;">
    <button type="submit" name="create_facture" class="btn-primary">Créer la facture</button>
</div>
</form>

<p class="muted">Astuce: vous pouvez ajouter/supprimer des lignes produit, les montants se recalculent automatiquement.</p>
</div>

<script>
const PRODUCTS = <?php echo json_encode($productsList, JSON_UNESCAPED_UNICODE); ?>;
const productRowsEl=document.getElementById('productRows');
const grandTotalEl=document.getElementById('grandTotal');
const totalHiddenEl=document.getElementById('total_display');
const addRowBtn=document.getElementById('addRowBtn');

function fmt(n){return (Math.round(n*100)/100).toFixed(2)}

function makeRow(){
    const row=document.createElement('div');row.className='product-row';

    const sel=document.createElement('select');sel.name='product_id[]';sel.required=true;
    sel.innerHTML='<option value="">-- Sélectionner --</option>'+PRODUCTS.map(p=>`<option value="${p.id_produit}" data-price="${p.prix}">${p.nom}</option>`).join('');

    const price=document.createElement('input');price.type='number';price.readOnly=true;price.step='0.01';price.placeholder='0.00';
    const qty=document.createElement('input');qty.type='number';qty.name='quantity[]';qty.min='1';qty.value='1';qty.required=true;
    const line=document.createElement('input');line.type='number';line.readOnly=true;line.step='0.01';line.placeholder='0.00';
    const rm=document.createElement('button');rm.type='button';rm.textContent='Supprimer';rm.className='btn btn-danger';rm.style.gridColumn='1/-1';rm.style.marginTop='6px';

    function update(){
        const unit=parseFloat(sel.selectedOptions[0]?.dataset.price||'0');
        const q=parseInt(qty.value||'0',10);
        price.value=unit?fmt(unit):'';
        line.value=unit&&q>0?fmt(unit*q):'';
        recalcTotal();
    }

    sel.addEventListener('change',update);
    qty.addEventListener('input',update);
    rm.addEventListener('click',()=>{row.remove();recalcTotal()});

    row.append(sel,price,qty,line,rm);
    return row;
}

function recalcTotal(){
    let t=0;
    productRowsEl.querySelectorAll('.product-row input[placeholder="0.00"]:last-of-type').forEach(el=>{
        const v=parseFloat(el.value||'0');if(!isNaN(v))t+=v;
    });
    grandTotalEl.textContent=fmt(t)+' €';
    totalHiddenEl.value=fmt(t);
}

addRowBtn.addEventListener('click',()=>productRowsEl.appendChild(makeRow()));
productRowsEl.appendChild(makeRow());
</script>
</body>
</html>