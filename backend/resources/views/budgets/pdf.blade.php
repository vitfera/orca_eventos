<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orçamento {{ $budget->event_name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Orçamento: {{ $budget->event_name }}</h2>
    <p>Data do Evento: {{ $budget->event_date ?? 'N/A' }}</p>
    <p>Observações: {{ $budget->remarks ?? 'Nenhuma' }}</p>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantidade</th>
                <th>Valor Unitário</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budget->items as $bi)
                <tr>
                    <td>{{ $bi->item->name }}</td>
                    <td>{{ $bi->quantity }}</td>
                    <td>{{ number_format($bi->unit_value, 2, ',', '.') }}</td>
                    <td>{{ number_format($bi->total_value, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total Geral</th>
                <th>{{ number_format($budget->items->sum('total_value'), 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <p>Link Público: {{ url('/public/budgets/'.$budget->public_link) }}</p>
</body>
</html>
