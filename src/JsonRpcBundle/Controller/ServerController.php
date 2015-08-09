<?php

namespace JsonRpcBundle\Controller;

use JsonRpcBundle\Logger;
use JsonRpcBundle\Server;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ServerController extends Controller
{

    public function handleAction(Request $request, $service) {
        $requestContent = $request->getContent();
        $logger = $this->get(Logger::ID)->getLogger();
        $logger->addInfo('request', array('content' => $requestContent));

        $server = $this->get(Server::ID);

        $authChecker = $this->get('security.authorization_checker');
        if (false === $authChecker->isGranted($service, $server)) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $cacheKey = base64_encode($service . $requestContent);        
        $redis = $this->get('snc_redis.default');
        if ($redis->exists($cacheKey)) {
            $result = unserialize($redis->get($cacheKey));
            $logger->addInfo('response', array('content' => $result));
            return new JsonResponse($result);
        }
        $result = $server->handle($requestContent, $service);
        $result = $result->toArray();
        $redis->set($cacheKey, serialize($result));
        $logger->addInfo('response', array('content' => $result));
        return new JsonResponse($result);
    }

}
