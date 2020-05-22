<?php
/**
 * User: salamander
 * Date: 18-12-12
 * Time: 上午11:12
 */

namespace Woshuo\YunXin\Api;


use Woshuo\YunXin\Exception\YunXinArgExcetption;

class User extends Base
{
    const GET_UINFOS_LIMIT = 200;

    const USER_NAME_LIMIT = 64;

    const USER_PROPS_LIMIT = 1024;

    const USER_ICON_LIMIT = 1024;

    const USER_TOKEN_LIMIT = 128;

    const USER_SIGN_LIMIT = 256;

    const USER_EMAIL_LIMIT = 64;

    const USER_BIRTH_LIMIT = 16;

    const USER_MOBILE_LIMIT = 32;

    const USER_GENDER_TYPES = [0, 1, 2];

    const USER_EX_LIMIT = 1024;


    /**
     * 验证参数
     * @param $accid
     * @param $name
     * @param array $props
     * @param $icon
     * @param $token
     * @param $sign
     * @param $email
     * @param $birth
     * @param $mobile
     * @param $gender
     * @param $ex
     * @throws YunXinArgExcetption
     */
    private function verifyUserInfo($accid, $name, array $props = [], $icon, $token, $sign,
                                    $email, $birth, $mobile, $gender, $ex)
    {
        $gender = intval($gender);
        $propsStr = json_encode($props);

        if (!$accid || !is_string($accid)) {
            throw new \LogicException('accid 不合法！');
        }
        if (strlen($name) > self::USER_NAME_LIMIT) {
            throw new YunXinArgExcetption('用户昵称最大长度' . self::USER_NAME_LIMIT . '字符！');
        }
        if (strlen($propsStr) > self::USER_PROPS_LIMIT) {
            throw new YunXinArgExcetption('用户props最大长度' . self::USER_PROPS_LIMIT . '字符！');
        }
        if (strlen($icon) > self::USER_ICON_LIMIT) {
            throw new YunXinArgExcetption('用户头像URL最大长度' . self::USER_ICON_LIMIT . '字符！');
        }
        if (strlen($token) > self::USER_TOKEN_LIMIT) {
            throw new YunXinArgExcetption('用户token最大长度' . self::USER_TOKEN_LIMIT . '字符！');
        }
        if (strlen($sign) > self::USER_SIGN_LIMIT) {
            throw new YunXinArgExcetption('用户sign最大长度' . self::USER_SIGN_LIMIT . '字符！');
        }
        if (strlen($email) > self::USER_EMAIL_LIMIT) {
            throw new YunXinArgExcetption('用户邮箱最大长度' . self::USER_EMAIL_LIMIT . '字符！');
        }
        if (strlen($birth) > self::USER_BIRTH_LIMIT) {
            throw new YunXinArgExcetption('用户生日最大长度' . self::USER_BIRTH_LIMIT . '字符！');
        }
        if (strlen($mobile) > self::USER_MOBILE_LIMIT) {
            throw new YunXinArgExcetption('用户手机号最大长度' . self::USER_MOBILE_LIMIT . '字符！');
        }
        if (!in_array($gender, self::USER_GENDER_TYPES)) {
            throw new YunXinArgExcetption('用户性别不合法！');
        }
        if (strlen($ex) > self::USER_EX_LIMIT) {
            throw new YunXinArgExcetption('用户名片扩展最大长度' . self::USER_EX_LIMIT . '字符！');
        }
    }


    /**
     * 创建网易云通信ID
     * @param $accid
     * @param $name
     * @param array $props
     * @param string $icon
     * @param string $token
     * @param string $sign
     * @param string $email
     * @param string $birth
     * @param string $mobile
     * @param int $gender
     * @param string $ex
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function create($accid, $name, array $props = [], $icon = '', $token = '', $sign = '', $email = '', $birth = '',
                           $mobile = '', $gender = 0, $ex = '')
    {
        $this->verifyUserInfo($accid, $name, $props, $icon, $token, $sign,
            $email, $birth, $mobile, $gender, $ex);

        $res = $this->sendRequest('user/create.action', [
            'accid' => $accid,
            'name' => $name,
            'props' => json_encode($props),
            'icon' => $icon,
            'token' => $token,
            'sign' => $sign,
            'email' => $email,
            'birth' => $birth,
            'mobile' => $mobile,
            'gender' => $gender,
            'ex' => $ex,
        ]);
        return $res['info'];
    }

    /**
     * 网易云通信ID基本信息更新
     * @param $accid
     * @param array $props
     * @param string $token
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function update($accid, array $props = [], $token = '')
    {
        $this->verifyUserInfo($accid, '', $props, '', $token, '',
            '', '', '', 0, '');

        $res = $this->sendRequest('user/update.action', [
            'accid' => $accid,
            'props' => json_encode($props),
            'token' => $token,
        ]);
        return $res;
    }

    /**
     * 更新并获取新token
     * @param $accid
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function refreshToken($accid)
    {
        $this->verifyUserInfo($accid, '', [], '', '', '',
            '', '', '', 0, '');

        $res = $this->sendRequest('user/refreshToken.action', [
            'accid' => $accid,
        ]);
        return $res['info'];
    }

    /**
     * 封禁网易云通信ID
     * @param $accid
     * @param bool $kick
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function block($accid, $kick = false)
    {
        $this->verifyUserInfo($accid, '', [], '', '', '',
            '', '', '', 0, '');

        $res = $this->sendRequest('user/block.action', [
            'accid' => $accid,
            'needkick' => $kick,
        ]);
        return $res;
    }

    /**
     * 解禁网易云通信ID
     * @param $accid
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function unblock($accid)
    {
        $this->verifyUserInfo($accid, '', [], '', '', '',
            '', '', '', 0, '');

        $res = $this->sendRequest('user/unblock.action', [
            'accid' => $accid,
        ]);
        return $res;
    }

    /**
     * 更新用户名片
     * @param $accid
     * @param string $name
     * @param string $icon
     * @param string $sign
     * @param string $email
     * @param string $birth
     * @param string $mobile
     * @param string $gender
     * @param string $ex
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function updateUserInfo($accid, $name = '', $icon = '', $sign = '', $email = '',
                                   $birth = '', $mobile = '', $gender = '', $ex = '')
    {
        $this->verifyUserInfo($accid, $name, [], $icon, '', $sign,
            $email, $birth, $mobile, $gender, $ex);

        $res = $this->sendRequest('user/updateUinfo.action', [
            'accid' => $accid,
            'name' => $name,
            'icon' => $icon,
            'sign' => $sign,
            'email' => $email,
            'birth' => $birth,
            'mobile' => $mobile,
            'gender' => $gender,
            'ex' => $ex,
        ]);
        return $res;
    }

    /**
     * 获取用户名片，可以批量
     * @param array $accids
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function getUserInfos(array $accids)
    {
        if (empty($accids)) {
            throw new YunXinArgExcetption('查询用户不能为空！');
        }
        if (count($accids) > self::GET_UINFOS_LIMIT) {
            throw new YunXinArgExcetption('查询用户数量超过限制！');
        }
        $res = $this->sendRequest('user/getUinfos.action', [
            'accids' => json_encode($accids)
        ]);
        return $res['uinfos'];
    }

    /**
     * 设置桌面端在线时，移动端是否需要推送，客户端登录后才可以设置
     * @param $account 云信账号
     * @param $donnopOpen 桌面端在线时，移动端是否不推送： true:移动端不需要推送，false:移动端需要推送
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function setDonNop($account, $donnopOpen)
    {
        if (empty($account)) {
            throw new YunXinArgExcetption('用户账号不能为空！');
        }
        if (!isset($donnopOpen)) {
            throw new YunXinArgExcetption('是否推送状态参数不能为空！');
        }
        $res = $this->sendRequest('user/setDonnop.action', [
            'accid' => $account,
            'donnopOpen' => $donnopOpen
        ]);
        return $res;
    }

    /**
     * 设置或取消账号的全局禁言状态；
     * 账号被设置为全局禁言后，不能发送“点对点”、“群”、“聊天室”消息
     * @param $account 云信账号
     * @param $mute 是否全局禁言： true：全局禁言，false:取消全局禁言
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function mute($account, $mute)
    {
        if (empty($account)) {
            throw new YunXinArgExcetption('用户账号不能为空！');
        }
        if (!isset($mute)) {
            throw new YunXinArgExcetption('是否全局禁言状态参数不能为空！');
        }
        $res = $this->sendRequest('user/mute.action', [
            'accid' => $account,
            'mute' => $mute
        ]);
        return $res;
    }

    /**
     * 设置或取消账号是否可以发起音视频功能；
     * 账号被设置为禁用音视频后，不能发起点对点音视频、创建多人音视频、发起点对点白板、创建多人白板
     * @param $account 云信账号
     * @param $mute 是否全局禁言： true：全局禁用，false:取消全局禁用
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function muteAv($account, $mute)
    {
        if (empty($account)) {
            throw new YunXinArgExcetption('用户账号不能为空！');
        }
        if (!isset($mute)) {
            throw new YunXinArgExcetption('是否全局禁用音视频状态参数不能为空！');
        }
        $res = $this->sendRequest('user/muteAv.action', [
            'accid' => $account,
            'mute' => $mute
        ]);
        return $res;
    }

    /**
     * 设置黑名单/静音
     * 拉黑/取消拉黑；设置静音/取消静音
     * @param $account
     * @param $targetAcc 被加黑或加静音的帐号
     * @param $relationType 本次操作的关系类型,1:黑名单操作，2:静音列表操作
     * @param $value 操作值，0:取消黑名单或静音，1:加入黑名单或静音
     * @return mixed
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function setSpecialRelation($account, $targetAcc, $relationType, $value)
    {
        if (empty($account)) {
            throw new YunXinArgExcetption('用户账号不能为空！');
        }
        if (empty($targetAcc)) {
            throw new YunXinArgExcetption('被加黑或加静音的帐号不能为空！');
        }

        $res = $this->sendRequest('user/setSpecialRelation.action', [
            'accid' => $account,
            'targetAcc' => $targetAcc,
            'relationType' => $relationType,
            'value' => $value
        ]);
        return $res;
    }

    /**
     * 查看指定用户的黑名单和静音列表
     * 查看用户的黑名单和静音列表
     * @param $account
     * @return array
     * @throws YunXinArgExcetption
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Woshuo\YunXin\Exception\YunXinBusinessException
     * @throws \Woshuo\YunXin\Exception\YunXinInnerException
     * @throws \Woshuo\YunXin\Exception\YunXinNetworkException
     */
    public function listBlackAndMuteList($account)
    {
        if (empty($account)) {
            throw new YunXinArgExcetption('用户账号不能为空！');
        }
        $res = $this->sendRequest('user/listBlackAndMuteList.action', [
            'accid' => $account
        ]);
        return [
            // 被静音的账号列表
            'mutelist' => $res['mutelist'],
            // 加黑的账号列表
            'blacklist' => $res['blacklist']
        ];
    }
}
