<?php
	// Create a multidimensional array
	$quotes_en = array(
				array("Only a life lived for others is a life worthwhile.", "Albert Einstein"),
				array("As is a tale, so is life: not how long it is, but how good it is, is what matters.", "Seneca"),
				array("To know what is right and not to do it is the worst cowardice",""),
				array("Our greatest weakness lies in giving up. The most certain way to succeed is always to try just one more time.","Thomas A. Edison"),
				array("I'd rather attempt to do something great and fail than to attempt to do nothing and succeed.","Robert H. Schuller"),
				array("There is no passion to be found playing small — in settling for a life that is less than the one you are capable of living.","Nelson Mandela")
			);
	$quotes_th = array(
				array("ครั้งหนึ่งแม่บอกว่าคนเราก็ไม่ต่างจากตัวละครในหนังสือ ที่ต้องเผชิญกับเรื่องราวต่าง ๆ ในชีวิต และเมื่อผ่านพ้นมาได้ก็จะมีความลุ่มลึกในเนื้ออารมณ์ เป็นคนเต็มคนมากขึ้น และมองทุกอย่างเปลี่ยนไป แม่ชอบใช้ \"คำใหญ่\" กับกะทิ ฟังดูดี แม้จะเข้าใจยาก แต่ ณ นาทีนี้ กะทิรู้สึกจริง ๆ ว่าตัวเอง \"โต\" ขึ้น", "ความสุขของกะทิ"),
				array("การรู้จักเส้นทาง กับการเดินไปบนเส้นทางนั้น แตกต่างกัน","เดอะแมทริกซ์"),
				array("มีไม่กี่คนหรอกที่เห็นด้วยตา และรู้สึกด้วยหัวใจของตนเอง", "อัลเบิร์ต ไอน์สไตน์"),
				array("บ่อยครั้งที่จินตนาการ นำเราไปสู่โลกที่ไม่มีอยู่จริง แต่ถ้าปราศจากจินตนาการ เราก็จะไม่ได้ไปที่ไหนเลย","คาร์ล เซแกน"),
				array("ผมอยากได้คำวิจารณ์ที่เสียดแทงที่สุดของคนที่ฉลาด ดีกว่าคำชมเชยของมวลชนที่ไม่มีหัวคิดเลย","โยฮันเนส เคปเลอร์")
			);

	if($language=='en'){
		$quotes = $quotes_en;
	}

	if($language=='th'){
		$quotes = $quotes_th;
	}
	// Return a random quote from the array
	$rand_quote = array_rand($quotes, 1);
	$quote = $quotes[$rand_quote][0];	
	$quote_author = $quotes[$rand_quote][1];
?>