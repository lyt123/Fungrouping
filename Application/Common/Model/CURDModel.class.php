<?php
/**
 * User: lyt123
 * Date: 2016/9/7  20:41
 */
namespace Common\Model;

class CURDModel extends BaseModel
{
    protected $resourceFields = array();

    /**
     * 添加一条数据
     */
    public function addOne(array $data)
    {
        if($this->create($data))
            if($result = $this->add())
                return qc_json_success($result);

        if($this->resourceFields)
            delete_files(get_data_in_array($data, $this->resourceFields));

        $status = $this->getError();
        return qc_json_error($status? $status: "system error");
    }

    /**
     * 添加多条数据
     */
    public function addData(array $datum)
    {
        $this->patchValidate = true;
        if($this->create($datum)) {
            if($result = $this->addAll($datum))
                return qc_json_success($result);
        }

        if($this->resourceFields) {
            foreach($datum as $data) {
                delete_files(get_data_in_array($data, $this->resourceFields));
            }
        }
        return qc_json_error($this->getError());
    }

    /**
     * 修改数据
     */
    public function update($id, array $data, $key = 'id')
    {
        if($this->resourceFields)
            $new_resources = get_data_in_array($data, $this->resourceFields);

        if(isset($new_resources) && $new_resources)
            $old_resources = $this->field(array_keys($new_resources))
                ->where(array($key => $id))
                ->find();

        if($this->create($data)) {
            $result = $this->where(array($key => $id))->save();
            if(false !== $result) {
                if($result) {
                    if(isset($old_resources))
                        delete_files($old_resources);
                    return qc_json_success();
                }
                return qc_json_error('nothing update');
            }
        }

        if(isset($new_resources)) delete_files($new_resources);

        $status = $this->getError();
        return qc_json_error($status? $status: 'system_error');
    }

    /**
     * 删除数据
     */
    public function destroy($id, $key = 'id', $multi_row = false)
    {
        if($this->resourceFields) {
            $this->field($this->resourceFields)->where(array($key => $id));
            if($multi_row) {
                $resources = $this->select();
                delete_files($resources, $multi_row);
            }
            else {
                $resources = $this->find();
                delete_files($resources, $multi_row);
            }
        }

        $result = $this->where(array($key => $id))->delete();

        if($result)
            return qc_json_success();
        return qc_json_error();
    }

    /**
     * 获取数据
     */
    public function getData(array $where = null, array $fields = null, $is_multi = false)
    {
        if($fields) $this->field($fields);

        if($where)  $this->where($where);

        if($is_multi)
            return $this->select();
        return $this->find();
    }

    /**
     * 字段值+1
     */
    public function incOne($id, $inc_field, $key = 'id') {
        $result = $this->where(array($key => $id))
            ->setInc($inc_field, 1);
        if($result)
            return qc_json_success();
        return qc_json_error();
    }
    /**
     * 字段值-1
     */
    public function decOne($id, $inc_field, $key = 'id') {
        $result = $this->where(array($key => $id))
            ->setDec($inc_field, 1);
        if($result)
            return qc_json_success();
        return qc_json_error();
    }
}