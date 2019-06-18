<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/5
 * Time: 10:29 AM
 */

namespace EasySmartProgram\TemplateMessage;

use EasySmartProgram\Support\Component;
use EasySmartProgram\Support\Exception\InvalidArgumentException;

/**
 * Class Manager
 * @package EasySmartProgram\TemplateMessage
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Manager extends Component
{
    /**
     * 场景定义
     */
    const SCENE_TYPE_1 = 1;
    const SCENE_TYPE_2 = 2;
    const SCENE_TYPE_3 = 3;

    /**
     * @var array
     */
    protected $format = [
        'touser' => '',
        'touser_openId' => '',
        'template_id' => '',
        'data' => [],
        'page' => '',
        'scene_id' => '',
        'scene_type' => '',
        'ext' => [],
    ];

    /**
     * @var array
     */
    protected $required = [
        ['template_id'],
        ['data'],
        ['scene_id'],
        ['scene_type'],
        ['touser', 'touser_openId'],
    ];

    /**
     * @param int $offset
     * @param int $count
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list(int $offset, int $count)
    {
        return $this->http()->post('template/librarylist', ['offset' => $offset, 'count' => $count]);
    }

    /**
     * @param string $id
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $id)
    {
        return $this->http()->post('template/libraryget', ['id' => $id]);
    }

    /**
     * @param string $id
     * @param array  $keyword
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function add(string $id, array $keyword)
    {
        $params = [
            'id' => $id,
            'keyword_id_list' => json_encode($keyword)
        ];

        return $this->http()->post('template/templateadd', $params);
    }

    /**
     * @param string $templateId
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $templateId)
    {
        return $this->http()->post('template/templatedel', ['template_id' => $templateId]);
    }

    /**
     * @param int $offset
     * @param int $count
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTemplates(int $offset, int $count)
    {
        return $this->http()->post('template/templatelist', ['offset' => $offset, 'count' => $count]);
    }

    /**
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(array $data)
    {
        return $this->http()->post('template/send', $this->format($data));
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function format(array $data) : array
    {
        $format = [];
        foreach (array_keys($this->format) as $field) {
            isset($data[$field]) && $format[$field] = $data[$field];
        }

        !isset($format['scene_id']) && $format['scene_id'] = $data['form_id'] ?? null;
        !isset($format['scene_type']) && $format['scene_type'] = self::SCENE_TYPE_1;
        isset($format['data']) && is_array($format['data']) && $format['data'] = json_encode($format['data']);
        isset($format['ext']) && is_array($format['ext']) && $format['ext'] = json_encode($format['ext']);

        foreach ($this->required as $required) {
            $valid = false;
            foreach ($required as $field) {
                if (isset($format[$field])) {
                    $valid = true;
                    break;
                }
            }

            if (!$valid) {
                throw new InvalidArgumentException(sprintf('[%s]不能同时为空!', implode(',', $required)));
            }
        }

        return $format;
    }
}