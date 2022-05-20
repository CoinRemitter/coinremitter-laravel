<?php

namespace JalalLinuX;

trait InteractWithCoinremitter
{
    public function coinremitter()
    {
        return new Coinremitter(strtoupper($this->getCoinOfCoinremitterAttribute()), $this->getCredentialsAttribute());
    }

    public function getCredentialsAttribute()
    {
        return json_decode($this->attributes['credentials'], true);
    }

    public function setCredentialsAttribute($value)
    {
        $this->attributes['credentials'] =  is_iterable($value) ? json_encode($value) : $value;
    }

    abstract function getCoinOfCoinremitterAttribute(): string;
}
