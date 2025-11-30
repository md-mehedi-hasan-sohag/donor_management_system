<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body',
        'variables',
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    // Helper to replace variables
    public function render($data = [])
    {
        $body = $this->body;
        $subject = $this->subject;

        foreach ($data as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }
}
