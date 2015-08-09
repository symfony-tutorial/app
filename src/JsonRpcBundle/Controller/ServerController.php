<?php

namespace JsonRpcBundle\Controller;

use JsonRpcBundle\Logger;
use JsonRpcBundle\Server;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));

        $cacheKey = sprintf('jsonRpc_%s_%s', $service, $requestContent);
        $redis = $this->get('snc_redis.default');
        if ($redis->exists($cacheKey)) {
            $result = $serializer->serialize($redis->get($cacheKey), 'json');
            $logger->addInfo('response', array('content' => $result));
            return new Response($result);
        }
        $result = $server->handle($requestContent, $service);
        $result = $result->toArray();
        //var_dump($result);
        $result = $serializer->serialize($result, 'json');
        $redis->set($cacheKey, $result);
        $logger->addInfo('response', array('content' => $result));

        return new Response($result);
    }

}
