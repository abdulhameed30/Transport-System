<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    // تحديد اسم الجدول إذا كان مخالفا للاسم القياسي (اختياري طالما الاسم صحيح)
    protected $table = 'trips';

    // إلغاء تفعيل updated_at لأنها غير موجودة في جدول الـ Migration الخاص بك
    public $timestamps = false; 
    // ملاحظة: إذا كنت تريد استخدام created_at فقط، يمكنك تفعيل هذا السطر أيضاً:
    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = null;

    // السماح بتعبئة هذه الحقول عبر الـ Mass Assignment (مثل create أو update)
    protected $fillable = [
        'driver_id',
        'trip_date',
        'city_id',
        'destination',
        'flight_number',
        'workers_count',
        'notes',
        'status',
        'odometer_image',
        'stage_1_time',
        'stage_2_time',
        'stage_3_time',
        'stage_4_time',
    ];

    // تحويل أنواع البيانات (Casting) لكي يتعامل معها لاراول بالشكل الصحيح
    protected $casts = [
        
        'trip_date' => 'datetime',
        'stage_1_time' => 'datetime',
        'stage_2_time' => 'datetime',
        'stage_3_time' => 'datetime',
        'stage_4_time' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * علاقة الرحلة مع السائق (Driver)
     * افترضت أن جدول السائقين اسمه users أو drivers ولديك مودل اسمه Driver
     */
    public function driver()
    {
        // إذا كان السائق مرتبط بجدول الـ users استخدم User::class بدلاً من Driver::class
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
