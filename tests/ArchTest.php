<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('models should extend BaseModel')
    ->expect('\SmartCms\Reviews\Models')
    ->toExtend('\SmartCms\Core\Models\BaseModel');

arch('models should use HasFactory trait')
    ->expect('\SmartCms\Reviews\Models')
    ->toUseTrait('\Illuminate\Database\Eloquent\Factories\HasFactory');
