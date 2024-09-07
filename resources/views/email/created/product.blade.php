<x-mail::message>
    Hello {{ $product->created_by }},

    We are pleased to inform you that Successfully added products to the database.

    Product Details:
    - Product ID: {{ $product->id }}
    - Product name: {{ $product->name }}
    - Product description: {{ $product->description }}
    - Product title: {{ $product->title }}
    - Product price: {{ $product->price }}
    - Product discounted percent: {{ $product->discounted_percent }}
    - Product discounted price: {{ $product->discounted_price }}
    - Product image: {{ $product->main_image }}

    Good luck,
    Laravel Basic
</x-mail::message>
