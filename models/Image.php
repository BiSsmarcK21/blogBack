<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int $path
 * @property string|null $created_at
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['path'], 'required'],
            [['path'], 'string', 'max' => 255],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[PostImages]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getPosts(): ActiveQuery
    {
        return $this->hasMany(Post::class, ['id' => 'post_id'])
            ->viaTable('post_images', ['image_id' => 'id']);
    }

    public function upload(): bool|array
    {
        if ($this->validate()) {
            $paths = [];
            foreach ($this->imageFiles as $file) {
                $fileName = '@runtime/temp' . time() . '_' . $file->baseName . '.' .
                    $file->extension;
                $file->savaAs($fileName);
                $paths[] = $fileName;
            }
            return $paths;
        }
        return false;
    }
}
