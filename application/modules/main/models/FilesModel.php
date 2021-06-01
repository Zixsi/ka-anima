<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class FilesModel extends APP_Model
{

    private const TABLE = 'files';
    private const TABLE_LINK_FILES = 'link_files';

    public function __construct()
    {
        parent::__construct();
    }

    public function add($data = [])
    {
        $data['file_path'] = get_rel_path($data['file_path']);
        $data['full_path'] = get_rel_path($data['full_path']);
        $data['file_size'] = number_format($data['file_size'], 2, '.', '');
        $data['image_width'] = intval($data['image_width']);
        $data['image_height'] = intval($data['image_height']);

        if ($this->db->insert(self::TABLE, $data))
            return $this->db->insert_id();


        return false;
    }

    public function saveFileArray($data)
    {
        if (!empty($data)) {
            if (isset($data['image_size_str']))
                unset($data['image_size_str']);
            $data['ts'] = date('Y-m-d 00:00:00');

            return $this->add($data);
        }

        return false;
    }

    public function delete($id)
    {
        return $this->db->delete(self::TABLE, ['id' => $id]);
    }

    public function getByID($id)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE id = ?';
        if ($row = $this->db->query($sql, [$id])->row_array())
            return $row;

        return false;
    }

    public function list($filter = [], $order = [], $select = [])
    {
        $select = count($select) ? implode(', ', $select) : '*';
        $this->db->select($select);

        count($filter) ? $this->db->where($filter) : $this->db->where('id >', 0);
        foreach ($order as $key => $val)
            $this->db->order_by($key, $val);

        if ($res = $this->db->get(self::TABLE))
            return $res->result_array();

        return false;
    }

    public function addLink(int $id, int $id_item, string $type)
    {
        $data = [
            'file' => $id,
            'item' => $id_item,
            'item_type' => $type
        ];

        if ($this->db->insert(self::TABLE_LINK_FILES, $data))
            return $this->db->insert_id();

        return false;
    }

    public function deleteLink($id, $item, $type)
    {
        return $this->db->delete(self::TABLE_LINK_FILES, ['file' => $id, 'item' => $item, 'item_type' => $type]);
    }

    public function deleteLinkFile($id, $item_id, $type)
    {
        $ids = is_array($id) ? $id : [$id];
     
        foreach ($ids as $id) {
            if ($item = $this->getByID($id)) {
                $this->deleteLink($item['id'], $item_id, $type);
                $this->delete($item['id']);
                unlink($item['full_path']);
            }
        }
    }

    public function listLinkFiles($item, $type)
    {
        $sql = 'SELECT 
					f.id, f.file_type as type, f.file_name as name, f.file_path as path, f.orig_name, f.file_ext as ext, f.is_image 
				FROM 
					' . self::TABLE_LINK_FILES . ' as lf 
				LEFT JOIN 
					' . self::TABLE . ' as f ON(f.id = lf.file) 
				WHERE 
					lf.item = ? AND lf.item_type = ? 
				ORDER BY 
					f.is_image DESC, f.id ASC';

        if ($res = $this->db->query($sql, [$item, $type])->result_array())
            return $res;

        return false;
    }

    public function filesUpload(string $name, int $item_id, string $type, string $config_name)
    {
        $files = $_FILES[$name] ?? [];
        $cnt = count($files);
        $upload_files = [];

        if (isset($files['name'][0])) {
            try {
                $this->db->trans_begin();

                for ($i = 0; $i < $cnt; $i++) {
                    if (empty($files['name'][$i])) {
                        continue;
                    }
                    
                    $_FILES['file_tmp'] = [
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i]
                    ];

                    $this->load->config('upload');
                    $upload_config = $this->config->item($config_name);
                    $this->load->library('upload', $upload_config);

                    if ($this->upload->do_upload('file_tmp') == false) {
                        throw new Exception($this->upload->display_errors(), 1);
                        break;
                    } else {
                        $file = $this->upload->data();
                        $upload_files[] = $file;
                        if (($file_id = $this->saveFileArray($file)) === false) {
                            throw new Exception($this->getLastError(), 1);
                        }

                        if ($this->addLink($file_id, $item_id, $type) === false) {
                            throw new Exception($this->getLastError(), 1);
                        }

                        // $this->createThumb($file['full_path']);
                    }
                }
                
                if ($this->db->trans_status()) {
                    $this->db->trans_commit();
                } else {
                    throw new Exception('Add files error', 1);
                }
            } catch (Exception $e) {
                if (count($upload_files)) {
                    foreach ($upload_files as $file) {
                        if (is_file($file['full_path'])) {
                            unlink($file['full_path']);
                        }
                    }
                }

                $this->setLastError($e->getMessage());
                $this->db->trans_rollback();
                
                return false;
            }
        }

        return true;
    }

    public function createThumb($file, $width = 150, $height = 150)
    {
        if (!isset($file['is_image']) || (int) $file['is_image'] !== 1)
            return;

        $config_resize = [
            'source_image' => $file['full_path'],
            'new_image' => $file['file_path'] . $file['raw_name'] . '_tmp' . $file['file_ext'],
            'maintain_ratio' => true,
            'width' => (int) $width,
            'height' => (int) $height,
            'master_dim' => (((int) $file['image_width'] > (int) $file['image_height']) ? 'height' : 'width')
        ];

        $config_crop = [
            'source_image' => $config_resize['new_image'],
            'new_image' => $file['full_path'],
            'maintain_ratio' => false,
            'create_thumb' => true,
            'thumb_marker' => '_thumb',
            'width' => (int) $width,
            'height' => (int) $height,
        ];

        $this->image_lib->initialize($config_resize);
        if (!$this->image_lib->resize())
            throw new Exception($this->image_lib->display_errors(), 1);
        $this->image_lib->clear();

        $this->image_lib->initialize($config_crop);
        if (!$this->image_lib->crop()) {
            unlink($config_resize['new_image']);
            throw new Exception($this->image_lib->display_errors(), 1);
        }

        unlink($config_resize['new_image']);
        $this->image_lib->clear();
    }

}
