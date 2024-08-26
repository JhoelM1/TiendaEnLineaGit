<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://www.paypal.com/sdk/js?client-id=AZhbuOBrno0jDHJl-a2n5PSJ9ItxBl1p-DM4OqUByQFL5Hp9UrDMLZUHDP60mwTuEJZ4-jN0FnOJBDkb&currency=USD"></script>
</head>
<body>
<div id="paypal-button-container"></div>
<script>
    paypal.Buttons({
        style:{
            color:'blue',
            label: 'pay'
            },
                createOrder: function(data, actions){
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: 50
                            }
                        }]
                    });
                },
                onApprove: function(data, actions){
                    actions.order.capture().then(function(detalles){
                        window.location.href="completado.html"
                    });
                },
                onCancel: function(data){
                    alert("El pago se canceló");
                    console.log(data);
                }
    }).render('#paypal-button-container')
</script>
</body>
</html>