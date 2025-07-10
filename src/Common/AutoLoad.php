<?php
/**
 * Class auto load
 */

spl_autoload_register(function ($className) {
    // __DIR__이 “.../src/Common”을 가리키므로,
    // src/까지 올라가려면 한 단계 위로 이동해야 함.
    $srcDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR; // → .../src/

    // 처리할 DoveRunnerProxy 하위 폴더 목록 (공통/Common, 예외/Exception, 서비스/Service)
    $proxyDirs = ['Common', 'Exception', 'Service'];

    // 1) DoveRunnerProxy\ 네임스페이스 처리
    $proxyPrefix = 'DoveRunnerProxy\\';
    if (strncmp($proxyPrefix, $className, strlen($proxyPrefix)) === 0) {
        // “DoveRunnerProxy\Common\Util” 같은 형태에서 “Common\Util” 부분을 얻음
        $relativeClass = substr($className, strlen($proxyPrefix));
        // 최상위 하위 폴더명(예: Common, Exception, Service)과 나머지 클래스로 분리
        $parts = explode('\\', $relativeClass, 2);
        $top = $parts[0];                // Common, Exception, Service 중 하나
        $rest = $parts[1] ?? '';         // Util.php, ErrorCode.php, ProxyService.php 등

        if (in_array($top, $proxyDirs, true)) {
            // 파일 경로 예: .../src/Common/Util.php
            $file = $srcDir
                . $top . DIRECTORY_SEPARATOR
                . ($rest ? $rest . '.php' : $top . '.php');

            if (file_exists($file)) {
                require_once $file;
            }
        }
        return;
    }

    // 2) DoveRunner\ 네임스페이스 처리 → TokenSample 폴더 내부 클래스 로드
    $doverunnerPrefix = 'DoveRunner\\';
    if (strncmp($doverunnerPrefix, $className, strlen($doverunnerPrefix)) === 0) {
        // “DoveRunner\DoveRunnerDrmTokenClient” → “DoveRunnerDrmTokenClient”
        $relativeClass = substr($className, strlen($doverunnerPrefix));
        // 토큰샘플 경로 예: .../src/TokenSample/DoveRunnerDrmTokenClient.php
        $file = $srcDir
            . 'TokenSample' . DIRECTORY_SEPARATOR
            . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
        return;
    }

    // 3) 위 두 가지 네임스페이스가 아니면 로드 대상 아님
});
