<?php
/**
 * User: salamander
 * Date: 18-12-12
 * Time: 上午11:12
 */

namespace Woshuo\YunXin\Api;

use Woshuo\YunXin\Exception\YunXinArgExcetption;

class Friend extends Base
{
    /**
     * 加好友
     * @param $account 加好友发起者accid
     * @param $friendAccount 加好友接收者accid
     * @param $type 1直接加好友，2请求加好友，3同意加好友，4拒绝加好友
     * @param string $msg 加好友对应的请求消息，第三方组装，最长256字符
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function add($account, $friendAccount, $type, $msg = '')
    {
        $res = $this->sendRequest('friend/add.action', [
            'accid' => $account,
            'faccid' => $friendAccount,
            'type' => $type,
            'msg' => $msg
        ]);
        return $res['info'];
    }

    /**
     * 更新好友相关信息
     * 1.更新好友相关信息，如加备注名，必须是好友才可以
     * @param $account
     * @param $friendAccount
     * @param string $alias 给好友增加备注名，限制长度128，可设置为空字符串
     * @param string $ex 修改ex字段，限制长度256，可设置为空字符串
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function update($account, $friendAccount, $alias = '', $ex = '')
    {
        $res = $this->sendRequest('friend/update.action', [
            'accid' => $account,
            'faccid' => $friendAccount,
            'alias' => $alias,
            'ex' => $ex
        ]);
        return $res['info'];
    }

    /**
     * 删除好友
     * @param $account
     * @param $friendAccount
     * @param bool $isDeleteAlias 是否需要删除备注信息 默认false:不需要，true:需要
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function delete($account, $friendAccount, $isDeleteAlias = false)
    {
        $res = $this->sendRequest('friend/delete.action', [
            'accid' => $account,
            'faccid' => $friendAccount,
            'isDeleteAlias' => $isDeleteAlias
        ]);
        return $res['info'];
    }

    /**
     * 获取好友关系
     * 查询某时间点起到现在有更新的双向好友
     * @param $account
     * @param $updateTime 更新时间戳，接口返回该时间戳之后有更新的好友列表
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function get($account, $updateTime)
    {
        $res = $this->sendRequest('friend/get.action', [
            'accid' => $account,
            'updatetime' => $updateTime
        ]);
        return $res['info'];
    }
}
