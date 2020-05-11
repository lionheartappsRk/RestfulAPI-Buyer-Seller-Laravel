<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $seller)
    {
        return [
            //
            'identifier' => (int) $seller->id,
            'name' => (string) $seller->name,
            'email' => (string) $seller->email,
            'isVerified' => (int) $seller->verified,
            'creationDate' =>  $seller->created_at,
            'lastChange' => $seller->updated_at,
            'deletedDate' => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,


            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('sellers.show', $seller->id),

                ],
                [
                    'rel' => 'seller.categories',
                    'href' => route('sellers.categories.index', $seller->id),

                ],
                [
                    'rel' => 'seller.products',
                    'href' => route('sellers.products.index', $seller->id),

                ],
                [
                    'rel' => 'seller.buyer',
                    'href' => route('sellers.buyer.index', $seller->id),

                ],
                [
                    'rel' => 'seller.transactions',
                    'href' => route('sellers.transactions.index', $seller->id),

                ],
            ]

        ];
    }

    public static function originalAttribute($index)
    {

        $attributes = [
            'identifier' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttributes($index)
    {

        $attributes = [
            'id' =>    'identifier',
            'name' => 'name',
            'email' => 'email',
            'verified' =>   'isVerified',
            'created_at'  => 'creationDate',
            'updated_at'  => 'lastChange',
            'deleted_at'  => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
