<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Sale</title>
</head>

<body>


    <div class="container">
        <h1>Edit Sale</h1>
        <form action="{{ route('sales.update', $sale->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="customer_id">Customer:</label>
                <input type="text" name="customer_id" id="customer_id" class="form-control" value="{{ $sale->customer->name }}">
            </div>

            <div class="form-group">
                <label for="created_at">Date:</label>
                <input type="text" name="created_at" id="created_at" class="form-control" value="{{ $sale->created_at }}">
            </div>

            @foreach ($sale->products as $product)
                <div class="form-group">
                    <label for="products[{{ $product->id }}][quantity]">Product Quantity:</label>
                    <input type="text" name="products[{{ $product->id }}][quantity]" id="products[{{ $product->id }}][quantity]" class="form-control" value="{{ $product->pivot->quantity }}">
                </div>

                <div class="form-group">
                    <label for="products[{{ $product->id }}][discount]">Product Discount:</label>
                    <input type="text" name="products[{{ $product->id }}][discount]" id="products[{{ $product->id }}][discount]" class="form-control" value="{{ $product->pivot->discount }}">
                </div>

                <div class="form-group">
                    <label for="products[{{ $product->id }}][payment_method]">Product Payment Method:</label>
                    <input type="text" name="products[{{ $product->id }}][payment_method]" id="products[{{ $product->id }}][payment_method]" class="form-control" value="{{ $product->pivot->payment_method }}">
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Update Sale</button>
        </form>

    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>
